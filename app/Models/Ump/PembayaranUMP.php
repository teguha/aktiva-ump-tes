<?php

namespace App\Models\UMP;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Globals\Approval;
use App\Models\Globals\MenuFlow;
use App\Models\Master\Org\OrgStruct;
use App\Models\Model;
use App\Models\Traits\HasApprovals;
use App\Models\Traits\HasFiles;
use App\Models\Traits\RaidModel;
use App\Models\Traits\ResponseTrait;
use App\Models\Traits\Utilities;
use App\Models\UMP\PengajuanUMP;
use App\Models\UMP\PjUMP;
use App\Models\UMP\PerpanjanganUMP;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class PembayaranUMP extends Model
{
    use HasFactory, HasFiles, RaidModel, ResponseTrait, Utilities, HasApprovals;

    protected $table = 'trans_ump_pembayaran';

    protected $fillable = [
        'pengajuan_ump_id',
        'id_ump_pembayaran',
        'tgl_ump_pembayaran',
        'tgl_jatuh_tempo',
        'uraian',
        'status',
    ];

    protected $dates = [
        'tgl_ump_pembayaran',
        'tgl_jatuh_tempo',
    ];

     /*******************************
     ** MUTATOR
     *******************************/
    public function setTglUmpPembayaranAttribute($value)
    {
        $this->attributes['tgl_ump_pembayaran'] = Carbon::createFromFormat('d/m/Y', $value);
    }

    public function setTglJatuhTempoAttribute($value)
    {
        $this->attributes['tgl_jatuh_tempo'] = Carbon::createFromFormat('d/m/Y', $value);
    }

    /*******************************
     ** ACCESSOR
     *******************************/
    public function getShowTglPembayaranAttribute()
    {
        return Carbon::parse($this->tgl_pembayaran)->format('d/m/Y');
    }
    /*******************************
     ** RELATION
     *******************************/

    public function pengajuanUmp(){
        return $this->belongsTo(PengajuanUMP::class, 'pengajuan_ump_id');
    }

     public function scopeGrid($query)
    {
        return $query->latest();
    }

    public function scopeGridStatusCompleted($query)
    {
        return $query->where('status', 'completed')->latest();
    }

     public function scopeFilters($query)
    {
        return $query
        ->filterBy(['status'])
        ->when(
            $id_pengajuan = request()->post('id_pengajuan'),
            function($q) use ($id_pengajuan){
                $q->whereHas('pengajuanUmp', function($r) use ($id_pengajuan){
                    $r->whereHas('pengajuanPembelian', function($s) use ($id_pengajuan){
                        $s->where('id_pengajuan', 'like', '%'.$id_pengajuan.'%');
                    });
                });
            }
        )
        ->when(
            $tgl_pengajuan_start = request()->post('tgl_pengajuan_start'),
            function ($q) use ($tgl_pengajuan_start) {
                $q->when(
                    $tgl_pengajuan_end = request()->post('tgl_pengajuan_end'),
                    function ($q) use ($tgl_pengajuan_start, $tgl_pengajuan_end) {
                        $tgl_pengajuan_start = Carbon::createFromFormat('d/m/Y', $tgl_pengajuan_start)->startOfDay();
                        $tgl_pengajuan_end = Carbon::createFromFormat('d/m/Y', $tgl_pengajuan_end)->endOfDay();
                        $q->whereHas('pjUMP', function($q) use ($tgl_pengajuan_start, $tgl_pengajuan_end){
                            $q->whereHas('pengajuanUmp', function($r) use ($tgl_pengajuan_start, $tgl_pengajuan_end){
                                $r->whereHas('pengajuanPembelian', function($s) use ($tgl_pengajuan_start, $tgl_pengajuan_end){
                                    return $s->whereBetween('tgl_pengajuan', [$tgl_pengajuan_start, $tgl_pengajuan_end]);
                                });
                            });
                        });
                    }
                );
            }
        )
        ->when(
            $unit_kerja = request()->post('unit_kerja'),
            function($q) use ($unit_kerja){
                $q->whereHas('pengajuanUmp', function($r) use ($unit_kerja){
                    $r->whereHas('pengajuanPembelian', function($s) use ($unit_kerja){
                        return $s->where('unit_kerja', $unit_kerja);
                    });
                });
            }
        );
    }

    public function handleStoreOrUpdate($request)
    {
        $this->beginTransaction();
        try {
            $data = $request->all();
            $this->fill($data);
            $this->status = 'draft';
            $this->save();
            $this->saveFilesByTemp($request->uploads, 'ump.pembayaran-ump', 'uploads');
            $this->saveLogNotify();

            if ($request->is_submit) {
                // cek flow approval?
                if ($request->is_submit == 1) {
                    $flowApproval = $this->getFlowApproval($request->module);
                    if ($flowApproval->count() == 0) {
                        return $this->rollback(
                            [
                                'message' => 'Data Flow Approval tidak tersedia!'
                            ]
                        );
                    }
                }
                $this->handleSubmitSave($request);
            }
            $redirect = route(request()->get('routes') . '.index');
            return $this->commitSaved(compact('redirect'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function handleSubmitSave($request)
    {
        $this->beginTransaction();
        try {
            $this->update(['status' => 'waiting.approval']);
            $this->generateApproval($request->module); 
            $this->saveLogNotify();
            $this->update();

            $redirect = route(request()->get('routes').'.index');
            return $this->commitSaved(compact('redirect'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function handleApprove($request)
    {
        $this->beginTransaction();
        try {
            $this->approveApproval($request->module);
            if ($this->approvals()->whereIn('status', ['draft', 'rejected'])->count() == 0) {
                $this->update([
                    'status' => 'completed',
                ]);

                $this->pengajuanUmp->update([
                    'tgl_pembayaran' => $this->tgl_ump_pembayaran->format('d/m/Y'),
                    'tgl_jatuh_tempo' => $this->tgl_jatuh_tempo->format('d/m/Y'),
                ]);
                $this->savePjUMP();
                $this->deletePerpanjanganUMP();
                $this->deletePembatalanUMP();

            }
            $this->saveLogNotify();

            $redirect = route(request()->get('routes').'.index');
            $message = "Data berhasil diotorisasi";
            return $this->commitSaved(compact('redirect', 'message'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function savePjUMP(){
        return PjUMP::firstOrCreate(['pengajuan_ump_id' => $this->pengajuan_ump_id, 'created_by' => $this->created_by]);
    }

    public function deletePerpanjanganUMP()
    {
        if(isset($this->pengajuanUmp->perpanjanganUMP)){
            $this->pengajuanUmp->perpanjanganUMP()->where('status', '!=', 'completed')->delete();
        } 
    }

    public function deletePembatalanUMP()
    {
        if(isset($this->pengajuanUmp->pembatalanUMP)){
            $this->pengajuanUmp->pembatalanUMP()->where('status', '!=', 'completed')->delete();
        } 
    }

    public function handleReject($request)
    {
        $this->beginTransaction();
        try {
            $this->rejectApproval($request->module, $request->note);
            $this->update(['status' => 'rejected']);
            $this->saveLogNotify();

            $redirect = route(request()->get('routes').'.index');
            return $this->commitSaved(compact('redirect'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }


    public function saveLogNotify()
    {
        $data = \Base::getModules(request()->get('module')).' pada '.date('d/m/Y');
        $routes = request()->get('routes');
        switch (request()->route()->getName()) {
            case $routes.'.store':
                $this->addLog('Membuat Data '.$data);
                break;
            case $routes.'.update':
                if($this->status == 'waiting.approval'){
                    $this->addLog('Submit Data '.$data);
                    $this->addNotify([
                        'message' => 'Menunggu Otorisasi '.$data,
                        'url' => route($routes.'.approval', $this->id),
                        'user_ids' => $this->getNewUserIdsApproval(request()->get('module')),
                    ]);
                    break;
                }else{
                    $this->addLog('Mengubah Data '.$data);
                }
                break;
            case $routes.'.destroy':
                $this->addLog('Menghapus Data '.$data);
                break;
            case $routes.'.approve':
                $this->addLog('Menyetujui Data '.$data);
                $this->addNotify([
                    'message' => 'Menunggu Otorisasi '.$data,
                    'url' => route($routes.'.approval', $this->id),
                    'user_ids' => $this->getNewUserIdsApproval(request()->get('module')),
                ]);
                break;
            case $routes.'.verify':
                $this->addLog('Memverifikasi Data '.$data);
                $this->addNotify([
                    'message' => 'Menunggu Pembayaran '.$data,
                    'url' => route($routes.'.payment', $this->id),
                    'user_ids' => $this->findUserByRoles(['Kepala Departemen Treasuri']),
                ]);
                break;
            case $routes.'.pay':
                $this->addLog('Membayar Data '.$data);
                $this->addNotify([
                    'message' => 'Menunggu Konfirmasi '.$data,
                    'url' => route($routes.'.confirmation', $this->id),
                    'user_ids' => [$this->pic_staff]
                ]);
                break;
            case $routes.'.confirm':
                $this->addLog('Mengkonfirmasi '.$data);
                break;
            case $routes.'.revise':
                $this->addLog('Data '.$data.' perlu direvisi');
                break;
            case $routes.'.cancel':
                $this->addLog('Data '.$data.' dibatalkan');
                break;
        }
    }

    public function checkAction($action, $perms, $record=null)
    {
        $user = auth()->user();
        switch ($action) {
            case 'edit':
                $checkStatus = in_array($this->status, ['new','draft']);
                return $checkStatus && $user->checkPerms($perms.'.edit');
                break;
            case 'show':
                return $user->checkPerms($perms.'.view');
                break;
            case 'history':
                return $user->checkPerms($perms.'.view');
                break;
            case 'approval':
                $checkStatus = in_array($this->status, ['waiting.approval']);
                return $checkStatus && $user->checkPerms($perms.'.approve');
                break;
            case 'tracking':
                $checkApproval = $this->approval(request()->get('module'))->exists();
                return $checkApproval && $user->checkPerms($perms . '.view');
                break;
            case 'print':
                $checkApproval = $this->approval(request()->get('module'))->exists();
                return $checkApproval && $user->checkPerms($perms . '.view');
                break;
            case 'verification':
                $checkStatus = in_array($this->status, ['waiting verification']);
                return $checkStatus && $user->checkPerms($perms.'.verify'); //if can add new variety of permission, might use from permission model
                break;
            case 'payment':
                $checkStatus = in_array($this->status, ['waiting payment']);
            return $checkStatus && $user->checkPerms($perms.'.payment');
                break;
            case 'confirmation':
                $checkStatus = in_array($this->status, ['waiting confirmation']);
                return $checkStatus && ($user->checkPerms($perms.'.confirm') || $user->id == $this->pic_staff);
                break;
            case 'revision':
                $checkStatus = in_array($this->status, ['revision']);
                return $checkStatus && $user->checkPerms($perms.'.edit');
                break;
        }

        return false;
    }

}

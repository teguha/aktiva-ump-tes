<?php

namespace App\Models\Ump;

use App\Models\Model;
use App\Models\Traits\HasApprovals;
use App\Models\Traits\HasFiles;
use App\Models\Ump\PengajuanUmp;
use Carbon\Carbon;


class PembatalanUmp extends Model
{
    use HasFiles, HasApprovals;

    protected $table = 'trans_ump_pembatalan';

    protected $fillable = [
        'pengajuan_ump_id',
        'id_ump_pembatalan',
        'tgl_ump_pembatalan',
        'uraian',
        'status',
    ];

    protected $dates = [
        'tgl_ump_pembatalan',
        'tgl_pembatalan_pembayaran',
    ];

     /*******************************
     ** MUTATOR
     *******************************/
    public function setTglUmpPembatalanAttribute($value)
    {
        $this->attributes['tgl_ump_pembatalan'] = Carbon::createFromFormat('d/m/Y', $value);
    }

    /*******************************
     ** ACCESSOR
     *******************************/
    public function getShowTglPembatalanAttribute()
    {
        return Carbon::parse($this->tgl_pembatalan)->format('d/m/Y');
    }
    /*******************************
     ** RELATION
     *******************************/

    public function pengajuanUmp(){
        return $this->belongsTo(PengajuanUmp::class, 'pengajuan_ump_id');
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
            $code = request()->post('code'),
            function($q) use ($code){
                $q->whereHas('pjUmp', function($q) use ($code){
                    $q->whereHas('pengajuanUmp', function($r) use ($code){
                        $r->whereHas('aktiva', function($s) use ($code){
                            $s->where('code', 'like', '%'.$code.'%');
                        });
                    });

                });
            }
        )
        ->when(
            $date_start = request()->post('date_start'),
            function ($q) use ($date_start) {
                $q->when(
                    $date_end = request()->post('date_end'),
                    function ($q) use ($date_start, $date_end) {
                        $date_start = Carbon::createFromFormat('d/m/Y', $date_start)->startOfDay();
                        $date_end = Carbon::createFromFormat('d/m/Y', $date_end)->endOfDay();
                        $q->whereHas('pjUmp', function($q) use ($date_start, $date_end){
                            $q->whereHas('pengajuanUmp', function($r) use ($date_start, $date_end){
                                $r->whereHas('aktiva', function($s) use ($date_start, $date_end){
                                    return $s->whereBetween('date', [$date_start, $date_end]);
                                });
                            });
                        });
                    }
                );
            }
        )
        ->when(
            $struct_id = request()->post('struct_id'),
            function($q) use ($struct_id){
                $q->whereHas('pjUmp', function($q) use ($struct_id){
                    $q->whereHas('pengajuanUmp', function($r) use ($struct_id){
                        $r->whereHas('aktiva', function($s) use ($struct_id){
                            return $s->where('struct_id', $struct_id);
                        });
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
            $this->saveFilesByTemp($request->uploads, 'ump.pembatalan-ump', 'uploads');
            $this->saveLogNotify();

            if ($request->is_submit) {
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
            // $this->code_ump = Self::generateIdPengajuan();
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
                $this->deletePerpanjanganUMP();
                $this->deletePembayaranUMP();
            }
            $this->saveLogNotify();

            $redirect = route(request()->get('routes').'.index');
            $message = "Data berhasil diotorisasi";
            return $this->commitSaved(compact('redirect', 'message'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function deletePerpanjanganUMP()
    {
        if(isset($this->pengajuanUmp->perpanjanganUMP)){
            $this->pengajuanUmp->perpanjanganUMP()->where('status', '!=', 'completed')->delete();
        } 
    }

    public function deletePembayaranUMP()
    {
        if(isset($this->pengajuanUmp->pembayaranUMP)){
            $this->pengajuanUmp->pembayaranUMP()->where('status', '!=', 'completed')->delete();
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

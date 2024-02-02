<?php

namespace App\Models\Termin;

use App\Models\Aktiva\PembelianAktiva;
use App\Models\Master\Org\OrgStruct;
use App\Models\Model;
use App\Models\Sgu\PengajuanSgu;
use App\Models\Termin\DetailTermin;
use App\Models\Termin\TerminPembayaran;
use App\Models\Traits\HasApprovals;
use App\Models\Traits\HasFiles;
use Carbon\Carbon;

class Termin extends Model
{
    use HasFiles, HasApprovals;

    protected $table = 'trans_termin';

    protected $fillable = [
        'code_sgu',
        'aktiva_id',
        'struct_id',
        'code',
        'date',
        'perihal',
        'nominal_pembayaran',
        'status',
    ];

    protected $dates = [
        'date',
    ];

    public function setTglPengajuanAttribute($value)
    {
        $this->attributes['date'] = Carbon::createFromFormat('d/m/Y', $value);
    }

    public function setNominalPembayaranAttribute($value='')
    {
        $value = str_replace(['.',','], '', $value);
        $this->attributes['nominal_pembayaran'] = (int) $value;
    }

    /*******************************
     ** RELATION
     *******************************/

    public function aktiva() {
        return $this->belongsTo(PembelianAktiva::class, 'aktiva_id');
    }

    public function pengajuanSgu() {
        return $this->belongsTo(PengajuanSgu::class, 'code_sgu');
    }

    public function details() {
        return $this->hasMany(DetailTermin::class,'termin_id');
    }

    public function terminPembayaran() {
        return $this->hasOne(TerminPembayaran::class,'termin_id');
    }

    public function struct() {
        return $this->belongsTo(OrgStruct::class, 'struct_id');
    }


    /*******************************
     ** SCOPE
     *******************************/
    public function scopeGrid($query)
    {
        return $query->latest();
    }

    public function scopeGridStatusCompleted($query)
    {
        return $query->where('status', 'completed')->latest();
    }

    public function scopeGridPembayaranStatusCompleted($query)
    {
        return $query->whereHas('terminPembayaran', function ($q) {
            $q->where('status', 'completed');
        })->where('status', 'completed')->latest();
    }

    public function scopeFilters($query)
    {
        return $query
        ->filterBy(['status'])
        ->when(
            $code = request()->post('code'),
            function($q) use ($code){
                $q->whereHas('aktiva', function ($qq) use ($code){
                    $qq->where('code','like', '%' . $code . '%');
                })->orWhereHas('pengajuanSgu', function ($qq) use ($code){
                    $qq->where('code','like', '%' . $code . '%');
                });
                // return $q->where('code_sgu', $code);
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
                        $q->whereHas('aktiva', function($q) use ($date_start, $date_end){
                            return $q->whereBetween('date', [$date_start, $date_end]);
                        });
                    }
                );
            }
        )
        ->when(
            $struct_id = request()->post('struct_id'),
            function($q) use ($struct_id){
                return $q->where('struct_id', $struct_id);
            }
        );
    }

    /*******************************
     ** SAVING
     *******************************/
    public function handleStoreOrUpdate($request)
    {
        $this->beginTransaction();
        try {
            if ($request->is_submit) {
                $flowApproval = $this->getFlowApproval($request->module);
                if ($flowApproval->count()==0) {
                    return $this->rollback(
                        [
                            'message' => 'Data Flow Approval tidak tersedia!'
                        ]
                    );
                }
            }

            if($this->details->count()==0){
                return $this->rollback(
                    [
                        'message' => 'Detail Aktiva tidak boleh kosong!'
                    ]
                );
            }

            $data = $request->all();
            $this->status = 'draft';
            $this->fill($data);
            $this->save();
            $this->saveFilesByTemp($request->uploads, 'termin.pengajuan', 'uploads');
            $this->saveLogNotify();

            if ($request->is_submit == 1) {
                // redirect ke modal submit
                return $this->commitSaved(['redirectToModal' => route($request->routes.'.submit', $this->id)]);
            }
            $redirect = route(request()->get('routes') . '.index');
            return $this->commitSaved(compact('redirect'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function handleDetailStoreOrUpdate($request, DetailTermin $detail)
    {
        $this->beginTransaction();
        try {
            $detail->fill($request->all());
            $detail->total = (int) str_replace(['.',','], '', $request->nominal) + (int) str_replace(['.',','], '', $request->pajak);
            $detail->status = "Belum dibayar";
            $this->details()->save($detail);
            $this->status = 'draft';
            $this->save();
            $this->saveLogNotify();

            return $this->commitSaved();
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function handleDetailDestroy(DetailTermin $detail)
    {
        $this->beginTransaction();
        try {
            $this->saveLogNotify();
            $detail->delete();

            return $this->commitDeleted();
        } catch (\Exception $e) {
            return $this->rollbackDeleted($e);
        }
    }

    public function handleDestroy()
    {
        $this->beginTransaction();
        try {
            $this->saveLogNotify();
            $this->delete();

            return $this->commitDeleted();
        } catch (\Exception $e) {
            return $this->rollbackDeleted($e);
        }
    }

    public function handleSubmitSave($request)
    {
        $this->beginTransaction();
        try {
            $this->update(['status' => 'waiting.approval']);
            $this->generateApproval($request->module);
            $this->saveLogNotify();

            $redirect = route(request()->get('routes') . '.index');
            return $this->commitSaved(compact('redirect'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
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

    public function handleApprove($request)
    {
        $this->beginTransaction();
        try {
            $this->approveApproval($request->module);
            $this->update([
                    'status' => 'completed',
                ]);

            $this->saveLogNotify();

            $redirect = route(request()->get('routes').'.index');
            return $this->commitSaved(compact('redirect'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }


    /*******************************
     ** OTHER
     *******************************/

    public function saveLogNotify()
    {
        $data = \Base::getModules(request()->get('module')).' pada '.$this->date;
        $routes = request()->get('routes');
        switch (request()->route()->getName()) {
            case $routes.'.store':
                $this->addLog('Membuat Data '.$data);
                break;
            case $routes.'.update':
                $this->addLog('Mengubah Data '.$data);
                break;
            case $routes.'.destroy':
                $this->addLog('Menghapus Data '.$data);
                break;
            case $routes.'.submitSave':
                $this->addLog('Submit Data '.$data);
                $this->addNotify([
                    'message' => 'Menunggu otorisasi '.$data,
                    'url' => route($routes.'.approval', $this->id),
                    'user_ids' => $this->getNewUserIdsApproval(request()->get('module')),
                ]);
                break;
            case $routes.'.approve':
                $this->addLog('Menyetujui Data '.$data);
                $this->addNotify([
                    'message' => 'Menunggu otorisasi '.$data,
                    'url' => route($routes.'.approval', $this->id),
                    'user_ids' => $this->getNewUserIdsApproval(request()->get('module')),
                ]);
                break;
            case $routes.'.reject':
                $this->addLog('Menolak Data '.$data.' dengan alasan: '.request()->get('note'));
                break;
        }
    }

    public function checkAction($action, $perms)
    {
        $user = auth()->user();
        switch ($action) {
            case 'create':
                return $user->checkPerms($perms.'.create');

            case 'edit':
                $checkStatus = in_array($this->status, ['new','draft']);
                return $checkStatus && $user->checkPerms($perms.'.edit');

            case 'show':
            case 'history':
                return $user->checkPerms($perms.'.view');

            case 'delete':
                $checkStatus = in_array($this->status, ['new','draft', 'rejected']);
                return $checkStatus && $user->checkPerms($perms.'.delete');
            case 'revision':
                return $this->status == 'revision' && $user->checkPerms($perms.'.edit');
            case 'tracking':
                $checkApproval = $this->approval(request()->get('module'))->exists();
                return $checkApproval && $user->checkPerms($perms . '.view');
                break;
            case 'print':
                $checkApproval = $this->approval(request()->get('module'))->exists();
                return $checkApproval && $user->checkPerms($perms . '.view');
                break;
            case 'approval':
                if ($this->checkApproval(request()->get('module'))) {
                    $checkStatus = in_array($this->status, ['waiting.approval']);
                    return $checkStatus && $user->checkPerms($perms.'.approve');
                }
                break;
        }

        return false;
    }

}

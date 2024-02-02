<?php

namespace App\Models\MutasiAktiva;

use App\Models\Aktiva\Aktiva;
use App\Models\Master\Org\OrgStruct;
use App\Models\Model;
use App\Models\Traits\HasApprovals;
use App\Models\Traits\HasFiles;
use Carbon\Carbon;

class MutasiAktiva extends Model
{
    use HasFiles, HasApprovals;

    protected $table = 'trans_mutasi_aktiva';

    protected $fillable = [
        'code',
        'date',
        'from_struct_id',
        'to_struct_id',
        'description',
        'status',
    ];

    protected $dates = [
        'date'
    ];

    /*******************************
     ** MUTATOR
     *******************************/

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Carbon::createFromFormat('d/m/Y', $value);
    }

    /*******************************
     ** RELATION
     *******************************/

    public function fromStruct()
    {
        return $this->belongsTo(OrgStruct::class, 'from_struct_id');
    }

    public function toStruct()
    {
        return $this->belongsTo(OrgStruct::class, 'to_struct_id');
    }

    public function details()
    {
        return $this->hasMany(MutasiAktivaDetail::class, 'pengajuan_id');
    }
    function realization()
    {
        return $this->hasOne(PelaksanaanMutasi::class, 'mutasi_aktiva_id');
    }

    public function scopeGrid($query)
    {
        return $query->withCount('details');
    }

    public function scopeGridStatusCompleted($query)
    {
        return $query->where('status', 'completed')->latest();
    }

    public function scopeGridRealizationStatusCompleted($query)
    {
        return $query->whereHas('realization', function ($q) {
            $q->where('status', 'completed');
        })->where('status', 'completed')->latest();
    }

    public function scopeFilters($query)
    {
        return $query->filterBy(['from_struct_id', 'status']);
    }

    public function handleStoreOrUpdate($request)
    {
        $this->beginTransaction();
        try {
            $data = $request->all();
            $this->status = 'draft';
            $this->fill($data);
            $this->save();
            $this->saveLogNotify();
            $this->saveFilesByTemp($request->uploads, $request->module , 'uploads');
            if (!$request->is_parent) {
                if ($request->is_submit) {
                    if ($this->details->count() == 0) {
                        return $this->rollback(
                            [
                                'message' => 'Detail Aktiva tidak boleh kosong!'
                            ]
                        );
                    }
                    $flowApproval = $this->getFlowApproval($request->module);
                    if ($flowApproval->count() == 0) {
                        return $this->rollback(
                            [
                                'message' => 'Data Flow Approval tidak tersedia!'
                            ]
                        );
                    }
                    return $this->commitSaved(['redirectToModal' => route($request->routes . '.submit', $this->id)]);
                }
            }
            $redirect = route(request()->get('routes') . '.index');
            return $this->commitSaved(compact('redirect'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function handleRealizationStoreOrUpdate($request)
    {
        $this->beginTransaction();
        try {
            $realization = PelaksanaanMutasi::firstOrNew(
                [
                    'mutasi_aktiva_id'  => $this->id
                ]
            );
            $realization->fill($request->all());
            $realization->status = 'draft';
            $realization->save();
            $realization->saveFilesByTemp($request->uploads, $request->module , 'uploads');
            $this->status = 'draft';
            $this->save();
            $this->saveLogNotify();
            $redirect = route(request()->get('routes') . '.index');
            return $this->commitSaved(compact('redirect'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function handleDetailStoreOrUpdate($request)
    {
        $this->beginTransaction();
        try {
            $detail = MutasiAktivaDetail::firstOrNew([
                'pengajuan_id' => $this->id,
                'aktiva_id' => $request->aktiva_id,
            ]);
            $detail->save();
            $aktiva = Aktiva::find($request->aktiva_id);
            $aktiva->mutasi_aktiva_detail_id = $detail->id;
            $aktiva->save();
            $this->save();
            return $this->commitSaved();
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function handleDetailDestroy(MutasiAktivaDetail $detail)
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

    public function handleSubmitDetail($request)
    {
        $this->beginTransaction();
        try {
            if($this->details->count()==0){
                return $this->rollback(
                    [
                        'message' => 'Detail Barang tidak boleh kosong!'
                    ]
                );
            }

            $data = $request->all();
            $this->status = 'draft';
            $this->fill($data);
            $this->save();

            if ($request->is_submit) {
                if ($this->details->count() == 0) {
                    return $this->rollback(
                        [
                            'message' => 'Detail Aktiva tidak boleh kosong!'
                        ]
                    );
                }
                $flowApproval = $this->getFlowApproval($request->module);
                if ($flowApproval->count() == 0) {
                    return $this->rollback(
                        [
                            'message' => 'Data Flow Approval tidak tersedia!'
                        ]
                    );
                }
                return $this->commitSaved(['redirectToModal' => route($request->routes . '.submit', $this->id)]);
            }
            $this->saveLogNotify();
            $redirect = route($request->routes . '.index');
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

            $redirect = route(request()->get('routes') . '.index');
            return $this->commitSaved(compact('redirect'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }
    public function handleDestroy()
    {
        $this->beginTransaction();
        try {
            $this->details()->delete();
            $this->saveLogNotify();
            $this->delete();

            return $this->commitDeleted();
        } catch (\Exception $e) {
            return $this->rollbackDeleted($e);
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
                foreach ($this->details as $key => $detail) {
                    $detail->aktiva->update(
                        [
                            'struct_id'                 => $this->to_struct_id,
                            'mutasi_aktiva_detail_id'   => null,
                        ]
                    );
                }
            }
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

            $redirect = route(request()->get('routes') . '.index');
            return $this->commitSaved(compact('redirect'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function saveLogNotify()
    {
        $data = \Base::getModules(request()->get('module')) . ' pada ' . $this->date;
        $routes = request()->get('routes');
        switch (request()->route()->getName()) {
            case $routes . '.store':
                $this->addLog('Membuat Data ' . $data);
                break;
            case $routes . '.update':
                $this->addLog('Mengubah Data ' . $data);
                break;
            case $routes . '.destroy':
                $this->addLog('Menghapus Data ' . $data);
                break;
            case $routes . '.submitSave':
                $this->addLog('Submit Data ' . $data);
                $this->addNotify([
                    'message' => 'Menunggu otorisasi ' . $data,
                    'url' => route($routes . '.approval', $this->id),
                    'user_ids' => $this->getNewUserIdsApproval(request()->get('module')),
                ]);
                break;
            case $routes . '.approve':
                $this->addLog('Menyetujui Data ' . $data);
                $this->addNotify([
                    'message' => 'Menunggu otorisasi ' . $data,
                    'url' => route($routes . '.approval', $this->id),
                    'user_ids' => $this->getNewUserIdsApproval(request()->get('module')),
                ]);
                break;
            case $routes . '.reject':
                $this->addLog('Menolak Data ' . $data . ' dengan alasan: ' . request()->get('note'));
                break;
        }
    }

    public function checkAction($action, $perms)
    {
        $user = auth()->user();
        switch ($action) {
            case 'create':
                return $user->checkPerms($perms . '.create');
            case 'realization.create':
                $checkStatus = in_array($this->realization->status ?? 'new', ['new']);
                return $checkStatus && $user->checkPerms($perms . '.create');

            case 'edit':
                $checkStatus = in_array($this->status, ['new', 'draft', 'rejected']);
                return $checkStatus && $user->checkPerms($perms . '.edit');
            case 'realization.edit':
                $checkStatus = in_array($this->realization->status ?? 'new', ['draft', 'rejected']);
                return $checkStatus && $user->checkPerms($perms . '.edit');

            case 'show':
            case 'history':
            case 'realization.show':
                return $user->checkPerms($perms . '.view');
            case 'realization.history':
                return (($record->realization->status ?? 'new') !== 'new') && $user->checkPerms($perms . '.view');

            case 'delete':
            case 'detail.delete':
                $checkStatus = in_array($this->status, ['new', 'draft', 'rejected']);
                return $checkStatus && $user->checkPerms($perms . '.delete');
            case 'revision':
                return $this->status == 'revision' && $user->checkPerms($perms . '.edit');
            case 'tracking':
                $checkApproval = $this->approval(request()->get('module'))->exists();
                return $checkApproval && $user->checkPerms($perms . '.view');
                break;
            case 'print':
                $checkApproval = $this->approval(request()->get('module'))->exists();
                return $checkApproval && $user->checkPerms($perms . '.view');
            case 'realization.print':
                $checkApproval = $this->realization && $this->realization->approval(request()->get('module'))->exists();
                return $checkApproval && $user->checkPerms($perms . '.view');
            case 'approval':
                if ($this->checkApproval(request()->get('module'))) {
                    $checkStatus = in_array($this->status, ['waiting.approval']);
                    return $checkStatus && $user->checkPerms($perms . '.approve');
                }
                break;
            case 'realization.approval':
                if ($this->realization && $this->realization->checkApproval(request()->get('module'))) {
                    $checkStatus = in_array($this->realization->status ?? 'new', ['waiting.approval']);
                    return $checkStatus && $user->checkPerms($perms . '.approve');
                }
                break;
        }

        return false;
    }
}

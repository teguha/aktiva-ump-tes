<?php

namespace App\Models\OperationalCost;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Master\Org\OrgStruct;
use App\Models\Model;
use App\Models\Termin\Termin;
use App\Models\Traits\HasApprovals;
use App\Models\Traits\HasFiles;
use App\Models\Ump\PengajuanUmp;
use Carbon\Carbon;

class OperationalCost extends Model
{
    use HasFiles, HasApprovals;

    protected $table = 'trans_operationalCost';

    protected $fillable = [
        'code',
        'date',
        'struct_id',
        'skema_pembayaran',
        'cara_pembayaran',
        'sentence_start',
        'sentence_end',
        'status',
    ];

    protected $dates = [
        'date'
    ];

    /*******************************
     ** MUTATOR
     *******************************/
    function setDateAttribute($value)
    {
        $this->attributes['date'] = $value ? Carbon::createFromFormat('d/m/Y', $value) : null;
    }
    /*******************************
     ** ACCESSOR
     *******************************/
    public function getStructName()
    {
        return $this->struct ? ucwords($this->struct->name) : '';
    }

    public function getTglPengajuanLabelAttribute()
    {
        return !empty($this->date) ? Carbon::parse($this->date)->format('d/m/Y') : '-';
    }

    public function getTotalHarga()
    {
        if ($this->details()->count() != 0) {
            return $this->details()->sum('cost');
        }
        return 0;
    }

    public function getSkemaPembayaran()
    {
        $skema_pembayaran = $this->skema_pembayaran ?? null;
        $result = '';

        switch ($skema_pembayaran) {
            case 'termin':
                $result = '<span class="badge badge-warning" style="color:white">Termin</span>';
                break;
            case 'ump';
                $result = '<span class="badge badge-primary" style="color:white">UMP</span>';
                break;
            default:
                $result = 'aa';
        }
        return $result;
    }

    /*******************************
     ** RELATION
     *******************************/
    public function struct()
    {
        return $this->belongsTo(OrgStruct::class, 'struct_id');
    }

    public function details()
    {
        return $this->hasMany(OperationalCostDetail::class, 'pengajuan_id');
    }

    /*******************************
     ** SCOPE
     *******************************/
    public function scopeGrid($query)
    {
        return $query;
    }

    public function scopeGridStatusCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFilters($query)
    {
        return $query
            ->filterBy(
                [
                    'code',
                ]
            )
            ->filterBy(
            [
                'struct_id',
                'status',
            ],
            '='
        );
    }

    public function scopeMonitoringFilters($query)
    {
        $request = request();
        return $query
            ->when(
                $id_aset = $request->id_aset,
                function ($q) use ($id_aset) {
                    $q->where('code', 'like', '%' . $id_aset . '%');
                }
            )->when(
                $thn_perolehan_aset = $request->thn_perolehan_aset,
                function ($q) use ($thn_perolehan_aset) {
                    $q->where('date', 'like', '%' . $thn_perolehan_aset . '%');
                }
            )->when(
                $jenis_aset = $request->jenis_aset,
                function ($q) use ($jenis_aset) {
                    $q->whereHas('aset', function ($q) use ($jenis_aset) {
                        $q->where('jenis_aset', $jenis_aset);
                    });
                }
            )->when(
                $skema_pembayaran = $request->skema_pembayaran,
                function ($q) use ($skema_pembayaran) {
                    $q->where('skema_pembayaran', 'like', '%' . $skema_pembayaran . '%');
                }
            );
    }

    public function scopeLaporanFilters($query)
    {
        $request = request();
        return $query
            ->when(
                $id_aset = $request->id_aset,
                function ($q) use ($id_aset) {
                    $q->where('code', 'like', '%' . $id_aset . '%');
                }
            )->when(
                $thn_perolehan_aset = $request->thn_perolehan_aset,
                function ($q) use ($thn_perolehan_aset) {
                    $q->where('date', 'like', '%' . $thn_perolehan_aset . '%');
                }
            )->when(
                $jenis_aset = $request->jenis_aset,
                function ($q) use ($jenis_aset) {
                    $q->whereHas('aset', function ($q) use ($jenis_aset) {
                        $q->where('jenis_aset', $jenis_aset);
                    });
                }
            )->when(
                $skema_pembayaran = $request->skema_pembayaran,
                function ($q) use ($skema_pembayaran) {
                    $q->where('skema_pembayaran', 'like', '%' . $skema_pembayaran . '%');
                }
            )
            ->when(
                $status = $request->status,
                function ($q) use ($status) {
                    if ($status == '1') {
                        $q->where('status', 'aktif');
                    } elseif ($status === '0') {
                        $q->where('status', 'nonaktif');
                    }
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
            $data = $request->all();
            $this->skema_pembayaran = 'termin';
            $this->status = 'draft';
            $this->fill($data);
            $this->save();
            $this->saveLogNotify();
            $redirect = route(request()->get('routes') . '.index');
            return $this->commitSaved(compact('redirect'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function handleSubmitDetail($request)
    {
        $this->beginTransaction();
        try {

            if ($this->details->count() == 0) {
                return $this->rollback(
                    [
                        'message' => 'Detail Biaya Operational tidak boleh kosong!'
                    ]
                );
            }
            // update kalimat pembuka dan kalimat penutup
            $this->update([
                'sentence_start' => $request->sentence_start,
                'sentence_end'  => $request->sentence_end
            ]);
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

                // redirect ke modal submit
                return $this->commitSaved(['redirectToModal' => route($request->routes . '.submit', $this->id)]);
            }
            $this->saveLogNotify();
            $redirect = route($request->routes . '.index');
            return $this->commitSaved(compact('redirect'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function handleDetailStoreOrUpdate($request, OperationalCostDetail $detail)
    {
        $this->beginTransaction();
        try {
            $detail->fill($request->all());
            if (!empty($request->cost)) {
                $detail['cost'] = str_replace('.', '', $request->cost);
            }
            $this->details()->save($detail);
            $this->status = 'draft';
            $this->save();
            $this->saveLogNotify();

            return $this->commitSaved();
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
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

    public function handleDetailDestroy(OperationalCostDetail $detail)
    {
        $this->beginTransaction();
        try {
            $this->saveLogNotify();
            $detail->delete();

            return $this->commitDeleted([
                'redirect' => route(request()->routes . '.detail', $this->id)
            ]);
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
            $this->update(['status' => 'cancelled']);
            $this->saveLogNotify();

            $redirect = route(request()->get('routes') . '.index');
            return $this->commitSaved(compact('redirect'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function handleRevision($request)
    {
        $this->beginTransaction();
        try {
            $this->rejectApproval($request->module, $request->note);
            $this->update(['status' => 'revision']);
            $this->saveLogNotify();
            $redirect = route(request()->get('routes') . '.index');
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
                // if ($this->skema_pembayaran === 'ump') {
                //     $this->savePengajuanUmp();
                // } elseif ($this->skema_pembayaran === 'termin') {
                //     $this->saveTermin();
                // }
            }
            $this->saveLogNotify();

            $redirect = route(request()->get('routes') . '.index');
            return $this->commitSaved(compact('redirect'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function savePengajuanUmp()
    {
        return PengajuanUmp::firstOrCreate(['aktiva_id' => $this->id, 'struct_id' => $this->struct_id]);
    }

    public function saveTermin()
    {
        return Termin::firstOrCreate(['aktiva_id' => $this->id, 'struct_id' => $this->struct_id]);
    }

    public function saveLogNotify()
    {
        $data = 'Pengajuan Operational Cost pada ' . date('d/m/Y, H:i');
        $routes = request()->get('routes');
        switch (request()->route()->getName()) {
            case $routes . '.store':
                $this->addLog('Membuat Data ' . $data);
                break;
            case $routes . '.detailStore':
                $this->addLog('Membuat Detail Data ' . $data);
                break;
            case $routes . '.updateSummary':
                $this->addLog('Mengubah Data ' . $data);
                break;
            case $routes . '.detailUpdate':
                $this->addLog('Mengubah Detail Data ' . $data);
                break;
            case $routes . '.destroy':
                $this->addLog('Menghapus Data ' . $data);
                break;
            case $routes . '.detailDestroy':
                $this->addLog('Mengubah Detail Data ' . $data);
                break;
            case $routes . '.submitSave':
                $this->addLog('Simpan & Submit Data ' . $data);
                $this->addNotify([
                    'message' => 'Waiting Approval ' . $data,
                    'url' => route($routes . '.approval', $this->id),
                    'user_ids' => $this->getNewUserIdsApproval(request()->get('module')),
                ]);
                break;
            case $routes . '.approve':
                $this->addLog('Menyetujui Data ' . $data);
                $this->addNotify([
                    'message' => 'Menunggu Verifikasi ' . $data,
                    'url' => route($routes . '.approval', $this->id),
                    'user_ids' => $this->getNewUserIdsApproval(request()->get('module')),
                ]);
                break;
            case $routes . '.reject':
                $this->addLog('Menolak Data ' . $data . ' dengan alasan: ' . request()->get('note'));
                break;
        }
    }
    private function findUserByRoles($roles)
    {
        $role_ids = Role::whereIn('name', $roles)->get()->pluck('id');
        return User::whereHas('roles', function ($q) use ($role_ids) {
            $q->whereIn('id', $role_ids);
        })
            ->pluck('id')
            ->toArray();
    }

    public function checkAction($action, $perms)
    {
        $user = auth()->user();
        switch ($action) {
            case 'create':
                return $user->checkPerms($perms . '.create');

            case 'edit':
                $checkStatus = in_array($this->status, ['new', 'draft']);
                return $checkStatus && $user->checkPerms($perms . '.edit');

            case 'show':
            case 'history':
                return $user->checkPerms($perms . '.view');

            case 'delete':
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
                break;
            case 'approval':
                if ($this->checkApproval(request()->get('module'))) {
                    $checkStatus = in_array($this->status, ['waiting.approval']);
                    return $checkStatus && $user->checkPerms($perms . '.approve');
                }
                break;
        }

        return false;
    }
}

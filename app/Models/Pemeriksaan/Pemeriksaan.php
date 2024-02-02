<?php

namespace App\Models\Pemeriksaan;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Master\Org\OrgStruct;
use App\Models\Model;
use App\Models\Termin\Termin;
use App\Models\Traits\HasApprovals;
use App\Models\Traits\HasFiles;
use App\Models\Ump\PengajuanUmp;
use Carbon\Carbon;

class Pemeriksaan extends Model
{
    use HasFiles, HasApprovals;

    protected $table = 'trans_pemeriksaan';

    protected $fillable = [
        'status',
        'code',
        'date',
        'struct_id',
        'description',
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

    /*******************************
     ** RELATION
     *******************************/
    public function details()
    {
        return $this->hasMany(PemeriksaanDetail::class, 'pemeriksaan_id');
    }
    public function pemeriksa()
    {
        return $this->belongsToMany(
            User::class,
            'trans_pemeriksaan_pemeriksa',
            'pemeriksaan_id',
            'user_id'
        );
    }
    public function struct()
    {
        return $this->belongsTo(OrgStruct::class, 'struct_id');
    }
    /*******************************
     ** SCOPE
     *******************************/
    public function scopeGridStatusCompleted($query)
    {
        return $query->where('status', 'completed')->latest();
    }
    
    public function scopeFilters($query)
    {
        return $query
            ->filterBy(['code'])
            ->filterBy(
                [
                    'struct_id',
                    'status',
                ],
                '='
            );
    }

    /*******************************
     ** SAVING
     *******************************/
    public function handleStoreOrUpdate($request)
    {
        $this->beginTransaction();
        try {
            if ($request->is_parent) {
                $data = $request->all();
                $this->status = 'draft';
                $this->fill($data);
                $this->save();
                $this->pemeriksa()->sync($request->pemeriksa_ids ?? []);
            }
            $this->saveLogNotify();
            if (!$request->is_parent) {
                if ($request->is_submit) {
                    if ($this->details()->count() == 0) {
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

    public function handleSubmitDetail($request)
    {
        $this->beginTransaction();
        try {
            $this->saveLogNotify();
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
            $redirect = route($request->routes . '.index');
            return $this->commitSaved(compact('redirect'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function handleDetailStoreOrUpdate($request, PemeriksaanDetail $detail)
    {
        $this->beginTransaction();
        try {
            $detail->fill($request->all());
            $this->details()->save($detail);
            $detail->saveFilesByTemp($request->uploads, $request->module , 'uploads');
            $this->status = 'draft';
            $this->save();
            $this->saveLogNotify();

            return $this->commitSaved(
                [
                    'redirect' => route($request->routes . '.detail', $this->id)
                ]
            );
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

    public function handleDetailDestroy(PemeriksaanDetail $detail)
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
            }
            $this->saveLogNotify();

            $redirect = route(request()->get('routes') . '.index');
            return $this->commitSaved(compact('redirect'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function saveLogNotify()
    {
        $data = 'Pengajuan Pembelian pada ' . date('d/m/Y, H:i');
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
            case 'print':
                $checkApproval = $this->approval(request()->get('module'))->exists();
                return $checkApproval && $user->checkPerms($perms . '.view');
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

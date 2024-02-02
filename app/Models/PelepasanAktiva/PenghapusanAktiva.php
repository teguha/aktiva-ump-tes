<?php

namespace App\Models\PelepasanAktiva;

use App\Models\Master\Org\OrgStruct;
use App\Models\Model;
use App\Models\PelepasanAktiva\PenghapusanAktivaDetail;
use App\Models\Traits\HasApprovals;
use App\Models\Traits\HasFiles;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenghapusanAktiva extends Model
{
    use HasFiles, HasApprovals;

    protected $table = 'trans_penghapusan_aktiva';

    protected $fillable = [
        'code',
        'date',
        'struct_id',
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

    public function struct()
    {
        return $this->belongsTo(OrgStruct::class, 'struct_id');
    }

    public function details()
    {
        return $this->hasMany(PenghapusanAktivaDetail::class, 'pengajuan_id');
    }
    function realization()
    {
        return $this->hasOne(PelaksanaanPenghapusanAktiva::class, 'penghapusan_aktiva_id');
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
        return $query->filterBy(
            [
                'pengajuan_id',
                'status',
                'struct_id',
            ]
        );
    }
    public function handleStoreOrUpdate($request, $status = false)
    {
        $this->beginTransaction();
        try {
            $data = $request->all();
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

    public function handleDetailStoreOrUpdate($request)
    {
        $this->beginTransaction();
        try {
            $detail = PenghapusanAktivaDetail::firstOrNew([
                'pengajuan_id' => $this->id,
                'aktiva_id' => $request->aktiva_id,
            ]);
            $detail->save();
            $this->save();
            return $this->commitSaved();
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function handleDetailDestroy(PenghapusanAktivaDetail $detail)
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
    public function handleSubmitDetail($request)
    {
        $this->beginTransaction();
        try {
            $this->description = $request->description;
            $this->save();
            $this->saveLogNotify();
            $this->saveFilesByTemp($request->uploads, $request->module , 'uploads');
            if ($request->is_submit) {
                if ($this->details->count() == 0) {
                    return $this->rollback(
                        [
                            'message' => 'Detail Aktiva tidak boleh kosong!'
                        ]
                    );
                }
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
            $this->update([
                'status' => 'completed',
            ]);

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
                $checkStatus = in_array($this->status, ['new', 'draft']);
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
                $checkStatus = in_array($this->status, ['new', 'draft', 'rejected']);
                return $checkStatus && $user->checkPerms($perms . '.delete');
            case 'revision':
                return $this->status == 'revision' && $user->checkPerms($perms . '.edit');
            case 'tracking':
                $checkApproval = $this->approval(request()->get('module'))->exists();
                return $checkApproval && $user->checkPerms($perms . '.view');
            case 'realization.tracking':
                $checkApproval = $this->realization && $this->realization->approval(request()->get('module'))->exists();
                return $checkApproval && $user->checkPerms($perms . '.view');
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

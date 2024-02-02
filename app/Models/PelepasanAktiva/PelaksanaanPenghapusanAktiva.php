<?php

namespace App\Models\PelepasanAktiva;

use App\Models\Auth\User;
use App\Models\Master\Org\OrgStruct;
use App\Models\Model;
use App\Models\Traits\HasApprovals;
use App\Models\Traits\HasFiles;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PelaksanaanPenghapusanAktiva extends Model
{
    use HasFiles, HasApprovals;

    protected $table = 'trans_pelaksanaan_penghapusan_aktiva';

    protected $fillable = [
        'status',
        'penghapusan_aktiva_id',
        'code',
        'date',
        'description',
    ];

    protected $dates = [
        'date',
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
    function pengajuan() {
        return $this->belongsTo(PenghapusanAktiva::class, 'penghapusan_aktiva_id');
    }

    public function handleStoreOrUpdate($request)
    {
        $this->beginTransaction();
        try {
            $data = $request->all();
            $this->fill($data);
            $this->status = $request->is_submit ? 'waiting.approval':'draft';
            $this->save();
            $this->saveFilesByTemp($request->uploads, $request->module , 'uploads');
            if ($request->is_submit) {
                $this->generateApproval($request->module);
            }
            $this->saveLogNotify();
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

            case 'edit':
                $checkStatus = in_array($this->status, ['new', 'draft', 'rejected']);
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

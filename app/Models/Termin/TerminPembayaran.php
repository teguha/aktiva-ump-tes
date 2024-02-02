<?php

namespace App\Models\Termin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;
use App\Models\Master\Org\OrgStruct;
use App\Models\Traits\Utilities;
use App\Models\Traits\RaidModel;
use App\Models\Traits\ResponseTrait;
use App\Models\Auth\User;
use Carbon\Carbon;
use App\Models\Traits\HasApprovals;
use App\Models\Traits\HasFiles;
use App\Models\Globals\MenuFlow;
use App\Models\Globals\Approval;
use App\Models\Auth\Role;
use App\Models\Termin\Termin;


class TerminPembayaran extends Model
{
    use HasFactory, HasFiles,  RaidModel, ResponseTrait, Utilities, HasApprovals;

    protected $table = 'trans_termin_pembayaran';

    protected $fillable = [
        'termin_id',
        'status'
    ];

    /*******************************
     ** RELATION
     *******************************/

    public function termin(){
        return $this->belongsTo(Termin::class, 'termin_id');
    }

    /*******************************
     ** SCOPE
     *******************************/
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
                break;
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

<?php

namespace App\Models\Master\MataAnggaran;

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


class MataAnggaran extends Model
{
    use HasFactory, HasFiles,  RaidModel, ResponseTrait, Utilities, HasApprovals;

    protected $table = 'mata_anggaran';

    protected $fillable = [
        'mata_anggaran',
        'nama',
        'deskripsi',
        'status',
    ];


     /*******************************
     ** MUTATOR
     *******************************/


    /*******************************
     ** ACCESSOR
     *******************************/

    public function checkAction($action, $perms)
    {
        $user = auth()->user();
        switch ($action) {
            case 'create':
                return $user->checkPerms($perms . '.create');
                break;

            case 'edit':
                return $user->checkPerms($perms . '.edit');
                break;

            case 'show':
                return true;
                break;

            case 'delete':
                return $user->checkPerms($perms . '.delete');
                break;
        }

        return false;
    }



    /*******************************
     ** RELATION
     *******************************/


    /*******************************
     ** SCOPE
     *******************************/
    public function scopeGrid($query)
    {
        return $query->latest();
    }

    public function scopeFilters($query)
    {
        return $query->filterBy(['mata_anggaran'])
        ->filterBy(['nama']);
    }

    /*******************************
     ** SAVING
     *******************************/
    public function handleStoreOrUpdate($request)
    {
        $this->beginTransaction();
        try {
            $data = $request->all();
            // $this->status = 'draft';
            $this->fill($data);
            $this->save();
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
        }
    }

}

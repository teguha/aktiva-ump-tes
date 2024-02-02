<?php

namespace App\Models\Master\MasaPenggunaan;

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


class MasaPenggunaan extends Model
{
    use HasFactory, HasFiles,  RaidModel, ResponseTrait, Utilities, HasApprovals;

    protected $table = 'masa_penggunaan';

    protected $fillable = [
        'masa_penggunaan',
        'status',
    ];

    // protected $dates = [
    //     'date'
    // ];

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
                return $user->checkPerms($perms.'.create');
                break;

            case 'edit':
                return $user->checkPerms($perms.'.edit');
                break;

            case 'show':
                return true;
                break;

            case 'delete':
                return $user->checkPerms($perms.'.delete');
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
        return $query->filterBy(['masa_penggunaan']);
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
            // $data['module'] = $request->module;
            $this->fill($data);
            $this->save();
            $this->saveLogNotify();

            // if ($request->is_submit) {
            //     return $this->commitSaved(['redirectToModal' => route($request->routes.'.submit', $this->id)]);
            // }


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

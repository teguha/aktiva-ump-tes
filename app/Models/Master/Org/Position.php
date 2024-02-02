<?php

namespace App\Models\Master\Org;

use App\Imports\Master\PositionImport;
use App\Models\Auth\User;
use App\Models\Globals\TempFiles;
use App\Models\Model;

class Position extends Model
{
    protected $table = 'ref_positions';

    protected $fillable = [
        'location_id',
        'kelompok_id',
        'name',
        'code',
        'status',
        'parent_id'
    ];

    /*******************************
     ** MUTATOR
     *******************************/

    /*******************************
     ** ACCESSOR
     *******************************/

    /*******************************
     ** RELATION
     *******************************/

    public function location()
    {
        return $this->belongsTo(OrgStruct::class, 'location_id');
    }

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'kelompok_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'position_id');
    }

    public function parent()
    {
        return $this->belongsTo(Position::class, 'parent_id');
    }

    public function child()
    {
        return $this->hasMany(Position::class, 'parent_id');
    }

    /*******************************
     ** SCOPE
     *******************************/
    public function scopeGrid($query)
    {
        return $query->has('location')->latest();
    }

    public function scopeFilters($query)
    {
        return $query->filterBy(['name', 'status'])
        ->when(
            $location_id= request()->post('location_id'),
            function ($q) use ($location_id) {
                return $q->where('location_id', $location_id);
            }
        )
            ->when(
            $kelompok_id = request()->post('kelompok_id'),
                function ($q) use ($kelompok_id) {
                    return $q->where('kelompok_id', $kelompok_id);
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
            $this->fill($request->all());
            $this->code = $this->code ?: $this->getNewCode();
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
            if (!$this->canDeleted()) {
                throw new \Exception('#'.__('base.error.related'));
            }
            $this->saveLogNotify();
            $this->delete();

            return $this->commitDeleted();
        } catch (\Exception $e) {
            return $this->rollbackDeleted($e);
        }
    }

    public function handleImport($request)
    {
        $this->beginTransaction();
        try {
            $file = TempFiles::find($request->uploads['temp_files_ids'][0]);
            if (!$file || !\Storage::disk('public')->exists($file->file_path)) {
                $this->rollback('File tidak tersedia!');
            }

            $filePath = \Storage::disk('public')->path($file->file_path);
            \Excel::import(new PositionImport(), $filePath);

            $this->saveLogNotify();

            return $this->commitSaved();
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function saveLogNotify()
    {
        $data = $this->name;
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

    /*******************************
     ** OTHER FUNCTIONS
     *******************************/
    public function canDeleted()
    {
        if($this->users()->exists()) return false;

        return true;
    }

    public function getNewCode()
    {
        $max = static::max('code');
        return $max ? $max+1 : 1001;
    }

     public function checkAction($action, $perms)
    {
        $user = auth()->user();
        switch ($action) {
            case 'create':
                return $user->checkPerms($perms.'.create');
                break;

            case 'edit':
                $checkStatus = in_array($this->status, ['1','2', '3']);
                return $checkStatus && $user->checkPerms($perms.'.edit');
                break;

            case 'show':
                return true;
                break;

            case 'delete':
                $checkStatus = in_array($this->status, ['1']);
                return $checkStatus && $this->canDeleted() && $user->checkPerms($perms.'.delete');
                break;
        }

        return false;
    }
}

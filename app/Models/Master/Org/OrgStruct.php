<?php

namespace App\Models\Master\Org;

use App\Imports\Master\OrgStructImport;
use App\Models\Aktiva\Aktiva;
use App\Models\Globals\TempFiles;
use App\Models\Master\Aspect\Aspect;
use App\Models\Master\Geo\City;
use App\Models\Master\Risk\LastAudit;
use App\Models\Master\Risk\RiskAssessment;
use App\Models\Model;
use App\Models\Rkia\Summary;
use App\Models\SewaGunaUsaha\PengajuanSGU;

class OrgStruct extends Model
{
    protected $table = 'ref_org_structs';

    protected $fillable = [
        'parent_id',
        'level', //root, bod, division, branch
        'type', //1:presdir, 2:direktur, 3:ia division, 4:it division
        'name',
        'code',
        'phone',
        'address',
        'website',
        'email',
        'status',
        'city_id'
    ];

    /** MUTATOR **/

    /** ACCESSOR **/

    public function getShowLevelAttribute()
    {
        switch ($this->level) {
            case 'boc':
                return __('Pengawas');
                break;
            case 'bod':
                return __('Direktur');
                break;
            case 'division':
                return __('Divisi');
                break;
            case 'department':
                return __('Departemen');
                break;
            case 'branch':
                return __('Regional');
                break;
            case 'subbranch':
                return __('Outlet');
                break;
            case 'cash':
                return __('Kantor Kas');
                break;
            case 'group':
                return __('Grup Lainnya');
                break;

            default:
                return ucfirst($this->level);
                break;
        }
    }

    /** RELATION **/
    function assets() {
        return $this->hasMany(Aktiva::class, 'struct_id');
    }
    public function parent()
    {
        return $this->belongsTo(OrgStruct::class, 'parent_id');
    }

    public function parents()
    {
        return $this->belongsTo(OrgStruct::class, 'parent_id')->with('parent');
    }

    public function child()
    {
        return $this->hasMany(OrgStruct::class, 'parent_id')->orderBy('level');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function childs()
    {
        return $this->hasMany(OrgStruct::class, 'parent_id')->orderBy('level')->with('child');
    }

    public function childOfGroup()
    {
        return $this->belongsToMany(OrgStruct::class, 'ref_org_structs_groups', 'group_id', 'struct_id');
    }

    public function structGroup()
    {
        return $this->belongsToMany(OrgStruct::class, 'ref_org_structs_groups', 'struct_id', 'group_id');
    }

    public function positions()
    {
        return $this->hasMany(Position::class, 'location_id');
    }

    public function letterNo()
    {
        return $this->hasMany(LetterNo::class, 'department_id');
    }

    public function pengajuanSGU(){
        return $this->hasMany(PengajuanSGU::class);
    }

    /** SCOPE **/
    public function scopeFilters($query)
    {
        return $query->filterBy(['code', 'name', 'status'])->defaultOrderBy()
            ->when(
            $parent_id = request()->post('parent_id'),
                function ($q) use ($parent_id) {
                    return $q->where('parent_id', $parent_id);
                }
            );
    }


    public function scopeRoot($query)
    {
        return $query->where('level', 'root')->orderByRaw("CASE WHEN updated_at > created_at THEN updated_at ELSE created_at END DESC");
    }

    public function scopeBoc($query)
    {
        return $query->where('level', 'boc')->orderByRaw("CASE WHEN updated_at > created_at THEN updated_at ELSE created_at END DESC");
    }

    public function scopeBod($query)
    {
        return $query->where('level', 'bod')->orderByRaw("CASE WHEN updated_at > created_at THEN updated_at ELSE created_at END DESC");
    }

    public function scopePresdir($query)
    {
        return $query->bod()->where('type', 1)->orderByRaw("CASE WHEN updated_at > created_at THEN updated_at ELSE created_at END DESC");
    }

    public function scopeDirector($query)
    {
        return $query->bod()->where('type', '!=', 1)->orderByRaw("CASE WHEN updated_at > created_at THEN updated_at ELSE created_at END DESC");
    }

    public function scopeDivision($query)
    {
        return $query->where('level', 'division')->orderByRaw("CASE WHEN updated_at > created_at THEN updated_at ELSE created_at END DESC");
    }

    public function scopeDivisionIa($query)
    {
        return $query->division()->where('type', 3)->orderByRaw("CASE WHEN updated_at > created_at THEN updated_at ELSE created_at END DESC")->orderByRaw("CASE WHEN updated_at > created_at THEN updated_at ELSE created_at END DESC");
    }

    public function scopeDivisionIt($query)
    {
        return $query->division()->where('type', 4)->orderByRaw("CASE WHEN updated_at > created_at THEN updated_at ELSE created_at END DESC");
    }

    public function scopeDepartment($query)
    {
        return $query->where('level', 'department')->orderByRaw("CASE WHEN updated_at > created_at THEN updated_at ELSE created_at END DESC");
    }

    public function scopeDepartmentIa($query)
    {
        return $query->department()
                    ->whereHas('parent', function ($q) {
                        $q->divisionIa()->orderByRaw("CASE WHEN updated_at > created_at THEN updated_at ELSE created_at END DESC");
                    });
    }

    public function scopeBranch($query)
    {
        return $query->where('level', 'branch')->orderByRaw("CASE WHEN updated_at > created_at THEN updated_at ELSE created_at END DESC");
    }

    public function scopeSubbranch($query)
    {
        return $query->where('level', 'subbranch')->orderByRaw("CASE WHEN updated_at > created_at THEN updated_at ELSE created_at END DESC");
    }

    public function scopeCash($query)
    {
        return $query->where('level', 'cash')->orderByRaw("CASE WHEN updated_at > created_at THEN updated_at ELSE created_at END DESC");
    }

    public function scopePayment($query)
    {
        return $query->where('level', 'payment')->orderByRaw("CASE WHEN updated_at > created_at THEN updated_at ELSE created_at END DESC");
    }

    public function scopeGroup($query)
    {
        return $query->where('level', 'group')->orderByRaw("CASE WHEN updated_at > created_at THEN updated_at ELSE created_at END DESC");
    }

    public function scopeInAudit($query)
    {
        return $query->where(function ($q) {
            $q->where(function ($qq) {
                $qq->divisionIa();
            })->orWhere(function ($qq) {
                $qq->departmentIa();
            });
        });
    }

    /** SAVE DATA **/
    public function handleStoreOrUpdate($request, $level)
    {
        // dd($request->all());
        // dd($level->all());
        $this->beginTransaction();
        try {
            if (in_array($level, ['boc','bod','division','other'])) {
                if ($root = static::root()->first()) {
                    $this->phone = $root->phone;
                    $this->address = $root->address;
                }
            }
            $this->fill($request->all());
            $this->level = $level;
            $this->code = $this->code ?: $this->getNewCode($level);
            $this->save();
            if ($level == 'group') {
                $this->childOfGroup()->sync($request->division);
            }
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
            // if (!$this->canDeleted()) {
            //     return $this->rollback(__('base.error.related'));
            // }
            $this->saveLogNotify();
            $this->delete();

            return $this->commitDeleted();
        } catch (\Exception $e) {
            return $this->rollbackDeleted($e);
        }
    }

    public function handleImport($request, $level)
    {
        $this->beginTransaction();
        try {
            $file = TempFiles::find($request->uploads['temp_files_ids'][0]);
            if (!$file || !\Storage::disk('public')->exists($file->file_path)) {
                $this->rollback('File tidak tersedia!');
            }

            $filePath = \Storage::disk('public')->path($file->file_path);
            \Excel::import(new OrgStructImport($level), $filePath);

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
            case $routes.'.importSave':
                auth()->user()->addLog('Import Data Master Struktur Organisasi');
                break;
        }
    }

    /** OTHER FUNCTIONS **/
    public function canDeleted()
    {
        if(in_array($this->type, [1,2,3,4])) return false;
        if(in_array($this->level, ['root'])) return false;
        if($this->child()->exists()) return false;
        if($this->structGroup()->exists()) return false;
        if($this->positions()->exists()) return false;
        if($this->assets()->exists()) return false;
        // if(Aspect::where('object_id', $this->id)->exists()) return false;
        // if(RiskAssessment::where('object_id', $this->id)->exists()) return false;
        // if(LastAudit::where('object_id', $this->id)->exists()) return false;

        // if(Summary::where('object_id', $this->id)->exists()) return false;

        return true;
    }

    public function getNewCode($level)
    {
        switch ($level) {
            case 'root':
                $max = static::root()->max('code');
                return $max ? $max+1 : 1001;
                break;
            case 'boc':
                $max = static::boc()->max('code');
                return $max ? $max+1 : 1101;
                break;
            case 'bod':
                $max = static::bod()->max('code');
                return $max ? $max+1 : 2001;
                break;
            case 'division':
                $max = static::division()->max('code');
                return $max ? $max+1 : 3001;
            case 'department':
                $max = static::department()->max('code');
                return $max ? $max+1 : 4001;
                break;
            case 'branch':
                $max = static::branch()->max('code');
                return $max ? $max+1 : 5001;
                break;
            case 'subbranch':
                $max = static::subbranch()->max('code');
                return $max ? $max+1 : 6001;
                break;
            case 'cash':
                $max = static::cash()->max('code');
                return $max ? $max+1 : 7001;
            case 'payment':
                $max = static::payment()->max('code');
                return $max ? $max+1 : 8001;
            case 'group':
                $max = static::group()->max('code');
                return $max ? $max+1 : 9001;
        }
        return null;
    }

    public function getIdsWithChild()
    {
        $ids = [$this->id];
        foreach ($this->child as $child) {
            $ids = array_merge($ids, $child->getIdsWithChild());
        }
        return $ids;
    }

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
}

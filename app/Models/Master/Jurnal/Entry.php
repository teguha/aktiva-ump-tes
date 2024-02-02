<?php

namespace App\Models\Master\Jurnal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;
use App\Models\Traits\Utilities;
use App\Models\Traits\RaidModel;
use App\Models\Traits\ResponseTrait;
use App\Models\Auth\User;
use Carbon\Carbon;
use App\Models\Traits\HasApprovals;
use App\Models\Traits\HasFiles;
use App\Models\Master\Jurnal\COA;

class Entry extends Model
{
    use HasFactory, HasFiles,  RaidModel, ResponseTrait, Utilities, HasApprovals;

    protected $table = 'jurnal_entries';

    protected $fillable = [
        'id_template',
        'id_coa',
        'jenis',
    ];

     /*******************************
     ** MUTATOR
     *******************************/


    /*******************************
     ** ACCESSOR
     *******************************/
    public function getNamaAkun(){
        return $this->coa->nama_akun;
    }

    public function getKodeAkun(){
        return $this->coa->kode_akun;
    }
    
    public function getTipeAkun(){
        return $this->coa->tipe_akun;
    }

    /*******************************
     ** RELATION
     *******************************/

    public function coa()
    {
        return $this->belongsTo(COA::class, 'id_coa');
    }

    /*******************************
     ** SCOPE
     *******************************/
    public function scopeGrid($query)
    {
        return $query->latest();
    }

    public function scopeFilters($query)
    {
        return $query->filterBy(['nama_akun']);
    }

    /*******************************
     ** SAVING
     *******************************/
    public function handleStoreOrUpdate($request)
    {
        $this->beginTransaction();
        try {
            $data = $request->all();
            $this->fill($data);
            $this->save();
            // $this->saveLogNotify();
            $redirect = route(request()->get('routes') . '.edit', $this->id_template);
            return $this->commitSaved(compact('redirect'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function handleDestroy()
    {
        $this->beginTransaction();
        try {
            $this->delete();
            return $this->commitDeleted();
        } catch (\Exception $e) {
            return $this->rollbackDeleted($e);
        }
    }

}

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


class DetailTermin extends Model
{
    use HasFactory, HasFiles,  RaidModel, ResponseTrait, Utilities, HasApprovals;

    protected $table = 'trans_detail_termin';

    protected $fillable = [
        'termin_id',
        'no_termin',
        'tgl_termin',
        'keterangan',
        'nominal',  
        'pajak',
        'total',
        'status'
    ];

    protected $dates = [
        'tgl_termin',
    ];

    public function setTglTerminAttribute($value)
    {
        $this->attributes['tgl_termin'] = Carbon::createFromFormat('d/m/Y', $value);
    }

    public function setNominalAttribute($value='')
    {
        $value = str_replace(['.',','], '', $value);
        $this->attributes['nominal'] = (int) $value;
    }

    public function setPajakAttribute($value='')
    {
        $value = str_replace(['.',','], '', $value);
        $this->attributes['pajak'] = (int) $value;
    }

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
     ** SAVING
     *******************************/
    public function handleStoreOrUpdate($request)
    {
        $this->beginTransaction();
        try {
            $data = $request->all();
            $this->fill($data);
            $this->total = $this->nominal + $this->pajak;
            $this->save();

            $redirect = route(request()->get('routes') . '.edit', $this->id_termin);
            return $this->commitSaved(compact('redirect'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    } 

}

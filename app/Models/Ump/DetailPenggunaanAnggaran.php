<?php

namespace App\Models\Ump;

use App\Models\Master\MataAnggaran\MataAnggaran;
use App\Models\Model;
use App\Models\Traits\HasApprovals;
use App\Models\Traits\HasFiles;
use App\Models\Ump\PjUmp;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class DetailPenggunaanAnggaran extends Model
{
    use HasFiles, HasApprovals;

    protected $table = 'detail_penggunaan_anggaran';

    protected $fillable = [
        'penggunaan',
        'id_pj_ump',
        'id_mata_anggaran',
        'nominal',
    ];

    /*******************************
     ** RELATION
     *******************************/

    public function pjUmp(){
        return $this->belongsTo(PjUmp::class, 'code_ump');
    }

    /*******************************
     ** SCOPE
     *******************************/

    public function scopeFilters($query)
    {

    }

    /*******************************
     ** SAVING
     *******************************/
    public function handleStoreOrUpdate($request)
    {
        $this->beginTransaction();
        try {
            $data = $request->all();
            if(!empty($request->nominal)){
                $data['nominal'] = str_replace('.', '', $request->nominal);
            }
            $this->fill($data);
            $this->save();

            $redirect = route(request()->get('routes') . '.edit', $this->id_pj_ump);
            return $this->commitSaved(compact('redirect'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function mataAnggaran()
    {
        return $this->hasOne(MataAnggaran::class, 'id', 'id_mata_anggaran');
    }


}

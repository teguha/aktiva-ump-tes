<?php

namespace App\Models\MutasiAktiva;

use App\Models\Aktiva\Aktiva;
use App\Models\Model;
use App\Models\MutasiAktiva\MutasiAktiva;
use App\Models\Traits\HasFiles;

class MutasiAktivaDetail extends Model
{
    use HasFiles;
    protected $table = 'trans_mutasi_aktiva_detail';

    protected $fillable = [
        'pengajuan_id',
        'aktiva_id',
        'status',
    ];

    /*******************************
     ** MUTATOR
     *******************************/

     /*******************************
     ** RELATION
     *******************************/
    public function pengajuan() {
        return $this->belongsTo(MutasiAktiva::class, 'pengajuan_id');
    }

    public function aktiva() {
        return $this->belongsTo(Aktiva::class, 'aktiva_id');
    }

    public function scopeGrid($query)
    {
        return $query->latest();
    }

    public function scopeFilters($query)
    {
        return $query->when(
            $keyword = request()->name,
            function ($q) use ($keyword) {
                $q->whereHas('aktiva', function ($qq) use ($keyword){
                    $qq->whereLike(['code', 'pembelianAktivaDetail.nama_aktiva'], $keyword);
                });
            }
        );
    }
}

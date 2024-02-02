<?php

namespace App\Models\PelepasanAktiva;

use App\Models\Aktiva\Aktiva;
use App\Models\Model;
use App\Models\PelepasanAktiva\PenghapusanAktiva;
use App\Models\Traits\HasFiles;


class PenghapusanAktivaDetail extends Model
{
    use HasFiles;
    protected $table = 'trans_penghapusan_aktiva_detail';

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
        return $this->belongsTo(PenghapusanAktiva::class, 'pengajuan_id');
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

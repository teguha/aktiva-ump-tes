<?php

namespace App\Models\PelepasanAktiva;

use App\Models\Aktiva\Aktiva;
use App\Models\Model;
use App\Models\Traits\HasFiles;


class PenjualanAktivaDetail extends Model
{
    use HasFiles;
    protected $table = 'trans_penjualan_aktiva_detail';

    protected $fillable = [
        'status',
        'pengajuan_id',
        'aktiva_id',
    ];

    /*******************************
     ** MUTATOR
     *******************************/
    /*******************************
     ** RELATION
     *******************************/
    public function pengajuan() {
        return $this->belongsTo(PenjualanAktiva::class, 'pengajuan_id');
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

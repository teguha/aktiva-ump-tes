<?php

namespace App\Models\Pemeriksaan;

use App\Models\Aktiva\Aktiva;
use App\Models\Model;
use App\Models\Traits\HasApprovals;
use App\Models\Traits\HasFiles;
use Carbon\Carbon;


class PemeriksaanDetail extends Model
{
    use HasFiles, HasApprovals;
    protected $table = 'trans_pemeriksaan_detail';

    protected $fillable = [
        'pemeriksaan_id',
        'aktiva_id',
        'condition',
        'description',
    ];

    protected $casts = [
    ];

    function aktiva() {
        return $this->belongsTo(Aktiva::class, 'aktiva_id');
    }
    public function pemeriksaan()
    {
        return $this->belongsTo(Pemeriksaan::class, 'pemeriksaan_id');
    }

    public function scopeFilters($query)
    {
        return $query->filterBy(['status']);
    }

    /*******************************
     ** SAVING
     *******************************/
}

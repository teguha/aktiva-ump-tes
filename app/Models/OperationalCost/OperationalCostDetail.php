<?php

namespace App\Models\OperationalCost;

use App\Models\Master\Barang\Vendor;
use App\Models\Master\Org\OrgStruct;
use App\Models\Model;
use App\Models\Traits\HasApprovals;
use App\Models\Traits\HasFiles;
use Carbon\Carbon;


class OperationalCostDetail extends Model
{
    use HasFiles, HasApprovals;
    protected $table = 'trans_operationalCost_details';

    protected $fillable = [
        'pengajuan_id',
        'vendor_id',
        'name',
        'cost',
        'tgl_pemesanan',
        'status',
    ];

    protected $dates = [
        'tgl_pemesanan',
    ];

    /*******************************
     ** MUTATOR
     *******************************/

    public function setTglPemesananAttribute($value)
    {
        $this->attributes['tgl_pemesanan'] = Carbon::createFromFormat('d/m/Y', $value);
    }


    public function pengajuan()
    {
        return $this->belongsTo(OperationalCost::class, 'pengajuan_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function getVendorName()
    {
        return Vendor::find($this->vendor_id)->name;
    }

    public function scopeGrid($query)
    {
        return $query->latest();
    }

    public function scopeFilters($query)
    {
        return $query->filterBy(['status']);
    }

    /*******************************
     ** SAVING
     *******************************/

    // ------------------------------------------------- //
    // Perhitungan
    // ------------------------------------------------- //
}

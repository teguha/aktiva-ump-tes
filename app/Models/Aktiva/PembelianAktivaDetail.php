<?php

namespace App\Models\Aktiva;

use App\Models\Master\Barang\Vendor;
use App\Models\Master\Org\OrgStruct;
use App\Models\Model;
use App\Models\Traits\HasApprovals;
use App\Models\Traits\HasFiles;
use Carbon\Carbon;


class PembelianAktivaDetail extends Model
{
    use HasFiles, HasApprovals;
    protected $table = 'trans_pembelian_aktiva_detail';

    protected $fillable = [
        'status',
        'pengajuan_pembelian_id',
        'vendor_id',
        'jenis_asset',
        'struct_id',
        'nama_aktiva',
        'merk',
        'no_seri',
        'jumlah_unit_pembelian',
        'harga_per_unit',
        'total_harga',
        'tgl_pembelian',
        'masa_penggunaan',
        'habis_masa_amortisasi',
        'tgl_mulai_amortisasi',
        'habis_masa_depresiasi',
        'tgl_mulai_depresiasi',
    ];

    protected $dates = [
        'tgl_pembelian',
        'habis_masa_amortisasi',
        'tgl_mulai_amortisasi',
        'habis_masa_depresiasi',
        'tgl_mulai_depresiasi',
    ];

    /*******************************
     ** MUTATOR
     *******************************/

    public function setTglPembelianAttribute($value)
    {
        $this->attributes['tgl_pembelian'] = Carbon::createFromFormat('d/m/Y', $value);
    }

    public function setHabisMasaAmortisasiAttribute($value)
    {
        if ($value != NULL) {
            $this->attributes['habis_masa_amortisasi'] = Carbon::createFromFormat('d/m/Y', $value);
        }
    }

    // public function setTglMulaiAmortisasiAttribute($value)
    // {
    //     if($value != NULL){
    //         $this->attributes['tgl_mulai_amortisasi'] = Carbon::createFromFormat('d/m/Y', $value);

    //     }
    // }

    public function setHabisMasaDepresiasiAttribute($value)
    {
        if ($value != NULL) {
            $this->attributes['habis_masa_depresiasi'] = Carbon::createFromFormat('d/m/Y', $value);
        }
    }

    // public function setTglMulaiDepresiasiAttribute($value)
    // {
    //     if($value != NULL){
    //         $this->attributes['tgl_mulai_depresiasi'] = Carbon::createFromFormat('d/m/Y', $value);
    //     }
    // }

    public function pengajuan()
    {
        return $this->belongsTo(PembelianAktiva::class, 'pengajuan_pembelian_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function struct()
    {
        return $this->belongsTo(OrgStruct::class, 'struct_id');
    }

    public function getVendorName()
    {
        return Vendor::find($this->vendor_id)->name;
    }

    public function getLokasiName()
    {
        return OrgStruct::find($this->struct_id)->name;
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
    public function calculateMasaManfaat($time, $max, $masa_penggunaan, $bulan_depresiasi_awal)
    {
        $masa_manfaat = 12;
        if ($time == 0) {
            $masa_manfaat = $masa_manfaat - $bulan_depresiasi_awal + 1;
        } else if ($time == $max) {
            $masa_manfaat = $bulan_depresiasi_awal == 1 ? 12 : $bulan_depresiasi_awal - 1;
        }
        return $masa_manfaat;
    }

    public function calculateNilaiBuku($time, $max, $harga_per_unit, $masa_penggunaan, $month)
    {
        if ($time > 0) {
            return $this->calculateNilaiBuku($time - 1, $max, $harga_per_unit, $masa_penggunaan, $month) - $this->calculateDepresiasi($time - 1, $max, $harga_per_unit, $masa_penggunaan, $month);
        }

        return $harga_per_unit;
    }

    public function calculateDepresiasi($time, $max, $harga_per_unit, $masa_penggunaan, $month)
    {
        return $this->calculateMasaManfaat($time, $max, $masa_penggunaan, $month) * $this->calculateDepresiasiPerBulan($time, $max, $harga_per_unit, $masa_penggunaan, $month);
    }

    public function calculateDepresiasiPerBulan($time, $max, $harga_per_unit, $masa_penggunaan, $month)
    {
        if ($time == $max) {
            return $this->calculateNilaiBuku($time, $max, $harga_per_unit, $masa_penggunaan, $month) / $this->calculateMasaManfaat($time, $max, $masa_penggunaan, $month);
        }
        return ($this->calculateNilaiBuku($time, $max, $harga_per_unit, $masa_penggunaan, $month) / 12 * 0.5);
    }

    public function calculateAmortisasiPerBulan($time, $max)
    {
        return $this->harga_per_unit * (1 / $this->masa_penggunaan);
    }
}

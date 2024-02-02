<?php

namespace App\Models\PembelianAktiva;

use App\Models\Aktiva\PembelianAktiva;
use App\Models\Aset\Aset;
use App\Models\Master\Barang\Vendor;
use App\Models\Master\Org\OrgStruct;
use App\Models\Model;
use App\Models\PembelianAktiva\PengajuanTangibleAsset;
use App\Models\Traits\HasApprovals;
use App\Models\Traits\HasFiles;
use Carbon\Carbon;


class PembelianAktivaIntangibleAsset extends Model
{
    use HasFiles, HasApprovals;
    protected $table = 'trans_intangible_asset';

    protected $fillable = [
        'id_aset',
        'vendor_id',
        'struct_id',
        'nama_aktiva',
        'merk',
        'no_seri',
        'jumlah_unit_pembelian',
        'harga_per_unit',
        'total_harga',
        'tgl_pembelian',
        'masa_pakai',
        'habis_masa_amortisasi',
        'tgl_mulai_amortisasi',
        'status',
    ];

    protected $dates = [
        'tgl_pembelian',
        'habis_masa_amortisasi',
        'tgl_mulai_amortisasi',
    ];

    public function getVendorName() {
        return Vendor::find($this->vendor_id)->name;
    }

    public function getLokasiName() {
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
    public function handleStoreOrUpdate($request, $id)
    {
        $this->beginTransaction();
        try {
            $data = $request->all();
            $data['id_aset'] = $id;
            // $data['nama_aktiva'] = !empty($request->id_aktiva) ? Barang::where('id', $request->id_aktiva)->first()->name : '';
            if(!empty($request->jumlah_unit_pembelian)){
                $data['jumlah_unit_pembelian'] = str_replace('.', '', $request->jumlah_unit_pembelian);
            }
            if(!empty($request->harga_per_unit)){
            $data['harga_per_unit'] = str_replace('Rp','',str_replace('.', '', $request->harga_per_unit));
            }
            if(!empty($request->harga_per_unit) and !empty($request->jumlah_unit_pembelian)){
                $data['total_harga'] = intval($data['harga_per_unit']*$data['jumlah_unit_pembelian']);
            }
            if(!empty($request->masa_pakai)){
                $data['masa_pakai'] =str_replace(' Bulan', '', $request->masa_pakai);
            }
            $tangible_asset =PengajuanTangibleAsset::where('id_aset', $id)->first();
            if(!empty($tangible_asset)){
                $tangible_asset->handleDestroy();
            }
            if($request->tgl_pembelian){
                $day = Carbon::createFromFormat("Y-m-d", $request->tgl_pembelian)->day;
                if ($day <= 15) {
                $result = Carbon::createFromFormat("Y-m-d", $request->tgl_pembelian)->format('Y-m');
                } else {
                $result = Carbon::createFromFormat("Y-m-d", $request->tgl_pembelian)->addMonths(1)->format('Y-m');
                }
            }
            $this->fill($data);
            $this->tgl_mulai_amortisasi = $result;
            $this->save();
            $this->saveLogNotify();
            if ($request->is_submit) {
                $aset = Aset::find($id);
                $record = PembelianAktiva::find($aset->code);
                $record->handleSubmitSave($request);
            }
            $redirect = route(request()->get('routes') . '.index');
            return $this->commitSaved(compact('redirect'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function saveLogNotify()
    {
        $aset = Aset::find($this->id_aset);
        $pengajuan_pembelian = PembelianAktiva::find($aset->code);
        $data = ' pada ' . date('d/m/Y, H:i');
        $routes = request()->get('routes');
        switch (request()->route()->getName()) {
            case $routes.'.edit.store':
                $pengajuan_pembelian->addLog('Membuat Detil Pengajuan PembelianAktiva '.$data);
                break;
            case $routes.'.edit.update':
                $pengajuan_pembelian->addLog('Mengubah Detil Pengajuan PembelianAktiva '.$data);
                break;
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

    public function calculateAmortisasiPerBulan($time, $max){
        return $this->harga_per_unit * (1/$this->masa_pakai);
    }

    public function calculateNilaiBuku($time, $max){
        if($time>0){
            return $this->calculateNilaiBuku($time-1, $max) - $this->calculateAmortisasi($time-1, $max);
        }
        return $this->harga_per_unit;
    }

    public function calculateMasaManfaat($time, $max){
        $masa_manfaat = 12 - (int) Carbon::parse($this->tgl_mulai_amortisasi)->month + 1;
        if ($time == 0){
            return $masa_manfaat;
        }
        else if($time!=$max){
            return 12;
        }
        return $this->masa_pakai - 12*($time-1) - $masa_manfaat;
    }

    public function calculateAmortisasi($time, $max){
        return $this->calculateMasaManfaat($time, $max) * $this->calculateAmortisasiPerBulan($time, $max);
    }
}

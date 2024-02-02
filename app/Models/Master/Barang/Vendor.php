<?php

namespace App\Models\Master\Barang;

use App\Imports\Master\ExampleImport;
use App\Models\Globals\TempFiles;
use App\Models\Master\Geo\City;
use App\Models\Master\Geo\Province;
use App\Models\Master\RekeningBank\RekeningBank;
use App\Models\Traits\RaidModel;
use App\Models\Traits\ResponseTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory, RaidModel, ResponseTrait;

    public $table = 'ref_vendor';

    protected $fillable = [
        "name", "address", "ref_province_id", "ref_city_id", "id_vendor",
        "telp", "email", "contact_person", "status",
    ];

    public function provinsi() {
        return $this->belongsTo(Province::class, 'ref_province_id');
    }

    public function kota() {
        return $this->belongsTo(City::class, "ref_city_id");
    }
    public function rekening()
    {
        return $this->hasMany(RekeningBank::class, 'vendor_id');
    }

    public function getProvinceName() {
        return $this->provinsi->name;
    }

    public function getCityName() {
        return $this->kota->name;
    }

    /*******************************
     ** MUTATOR
     *******************************/

    /*******************************
     ** ACCESSOR
     *******************************/

    /*******************************
     ** RELATION
     *******************************/
    public function barang()
    {
        return $this->hasMany(Barang::class, 'vendor_id', 'id');
    }

    // public function pembelian_aktiva()
    // {
    //     return $this->hasMany(PembelianAktiva::class, 'vendor_id', 'id');
    // }

    /*******************************
     ** SCOPE
     *******************************/
    public function scopeGrid($query)
    {
        return $query->latest();
    }

    public function scopeFilters($query)
    {
        return $query->filterBy(['name'])
        ->filterBy(['status']);
    }

    /*******************************
     ** SAVING
     *******************************/
    public function handleStoreOrUpdate($request)
    {
        $this->beginTransaction();
        try {
            $this->fill($request->all());
            $this->save();
            $this->storeRekening($request);
            $this->saveLogNotify();

            return $this->commitSaved();
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function handleDestroy()
    {
        $this->beginTransaction();
        try {
            $this->saveLogNotify();
            $this->delete();

            return $this->commitDeleted();
        } catch (\Exception $e) {
            return $this->rollbackDeleted($e);
        }
    }

    public function handleImport($request)
    {
        $this->beginTransaction();
        try {
            $file = TempFiles::find($request->uploads['temp_files_ids'][0]);
            if (!$file || !\Storage::disk('public')->exists($file->file_path)) {
                $this->rollback('File tidak tersedia!');
            }

            $filePath = \Storage::disk('public')->path($file->file_path);
            \Excel::import(new ExampleImport(), $filePath);

            $this->saveLogNotify();

            return $this->commitSaved();
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function saveLogNotify()
    {
        $data = $this->name;
        $routes = request()->get('routes');
        switch (request()->route()->getName()) {
            case $routes.'.store':
                $this->addLog('Membuat Data '.$data);
                break;
            case $routes.'.update':
                $this->addLog('Mengubah Data '.$data);
                break;
            case $routes.'.destroy':
                $this->addLog('Menghapus Data '.$data);
                break;
        }
    }

    public function checkAction($action, $perms)
    {
        $user = auth()->user();
        switch ($action) {
            case 'create':
                return $user->checkPerms($perms . '.create');
                break;

            case 'edit':
                return $user->checkPerms($perms . '.edit');
                break;

            case 'show':
                return true;
                break;

            case 'delete':
                $checkStatus = in_array($this->status, ['1']);
                return $checkStatus && $user->checkPerms($perms . '.delete');
                break;
        }

        return false;
    }

    public function storeRekening($request)
    {
        if ($request->rekening) {
            RekeningBank::where('vendor_id', $this->id)->delete();
            foreach ($request->rekening as $rekening) {
                RekeningBank::create([
                    'status' => $rekening['status'],
                    'vendor_id' => $this->id,
                    'nama_pemilik' => $rekening['nama_pemilik'],
                    // 'no_rekening' => $rekening['no_rekening'],
                    'bank_id' => $rekening['bank_id'],

                ]);
            }
        }
        else {
            RekeningBank::where('vendor_id', $this->id)->delete();
        }
    }


    public function getBankStatusName($status)
    {
        $result = '';

        switch ($status) {
            case 'active':
                $result = '<span class="badge badge-success text-center">Aktif</span>';
                break;
            case 'nonactive':
                $result = '<span class="badge badge-secondary text-center">Nonaktif</span>';
                break;
        }


        return $result;
    }

    /*******************************
     ** OTHER FUNCTIONS
     *******************************/
    public function canDeleted()
    {
        // if($this->barang->count() || $this->pembelian_aktiva->count()) return false;

        return true;
    }
}

<?php

namespace App\Models\Master\Bank;

// use App\Imports\Master\BankAccountImport;
use App\Models\Auth\User;
use App\Models\Globals\TempFiles;
use App\Models\Model;
use App\Models\Preparation\Fee\Fee;

class BankAccount extends Model
{
    protected $table = 'ref_bank_accounts';

    protected $fillable = [
        'user_id',
        'number',
        'bank',
    ];

    protected $appends = ['show_bank'];

    /*******************************
     ** MUTATOR
     *******************************/

    /*******************************
     ** ACCESSOR
     *******************************/
    public function getShowBankAttribute()
    {
        return $this->getBankNames($this->bank) ?? null;
    }

    /*******************************
     ** RELATION
     *******************************/
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    /*******************************
     ** SCOPE
     *******************************/
    public function scopeGrid($query)
    {
        return $query->latest();
    }

    public function scopeFilters($query)
    {
        return $query->filterBy(['number']);
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

    // public function handleImport($request)
    // {
    //     $this->beginTransaction();
    //     try {
    //         $file = TempFiles::getFileById($request->uploads['temp_files_ids'][0]);
    //         if (!$file) throw new \Exception('MESSAGE--File tidak tersedia!', 1);

    //         \Excel::import(new BankAccountImport(), $file);

    //         $this->saveLogNotify();

    //         return $this->commitSaved();
    //     } catch (\Exception $e) {
    //         return $this->rollbackSaved($e);
    //     }
    // }

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

    /*******************************
     ** OTHER FUNCTIONS
     *******************************/
    public function canDeleted()
    {
        // if($this->fees()->exists()) return false;

        return true;
    }

    public function getBankNames($key = null)
    {
        $data = [
            'bri' => 'BANK RAKYAT INDONESIA',
            'mandiri' => 'BANK MANDIRI',
            'bni' => 'BANK NEGARA INDONESIA',
            'btn' => 'BANK TABUNGAN NEGARA',
            'bca' => 'BANK CENTRAL ASIA',
            'maybank' => 'BANK MAYBANK INDONESIA',
            'ocbc' => 'BANK OCBC NISP',
            'permata' => 'BANK PERMATA',
        ];
        if (!is_null($key)) {
            return $data[$key] ?? null;
        }
        return $data;
    }
}

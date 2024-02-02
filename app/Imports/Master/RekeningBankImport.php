<?php

namespace App\Imports\Master;

use App\Models\Auth\User;
use App\Models\Master\RekeningBank\RekeningBank;
use App\Models\Master\RekeningBank\Bank;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class RekeningBankImport implements ToCollection, WithStartRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        // Validasi Template
        $row = $collection->first();
        if (empty($row[0]) || empty($row[1]) || empty($row[2]) || empty($row[3])|| empty($row[4])) {
            throw new \Exception("MESSAGE--File tidak tidak sesuai dengan template terbaru. Silahkan download template kembali!", 1);
        }

        // Insert Data
        $namaPemilik = null;
        $noRekening = null;
        $kcp = null;
        $bank = null;
        foreach ($collection as $rw => $row)
        {
            if ($rw > 0) {
                $namaPemilik = trim($row[1] ?? null);
                $noRekening = trim($row[2] ?? null);
                $kcp = trim($row[3] ?? null);
                $bank = trim($row[4] ?? null);

                $pemilik = User::where('name', 'LIKE', '%'.$namaPemilik.'%')->first(); // buat query dsisni mw ke email atw mana?
                $bank = Bank::where('name', 'LIKE', '%'.$bank.'%')->first();
                if ($bank && $pemilik) {
                    $rekening = new RekeningBank;
                    $rekening->user_id = $pemilik->id;
                    $rekening->no_rekening = $noRekening;
                    $rekening->kcp = $kcp;
                    $rekening->bank_id = $bank->id;
                    $rekening->save();
                }
            }
        }


        return $collection;
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 1;
    }
}

<?php

namespace App\Imports\Master;

use App\Models\Auth\User;
use App\Models\Master\Fee\BankAccount;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class BankAccountImport implements ToCollection, WithStartRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        // Validasi Template
        $row = $collection->first();
        if (empty($row[2]) || strtoupper($row[2]) != strtoupper('No Rekening')) {
            throw new \Exception("MESSAGE--File tidak tidak sesuai dengan template terbaru. Silahkan download template kembali!", 1);
        }

        // Maping Data
        $banks = (new BankAccount)->getBankNames();
        foreach ($collection as $rw => $row) 
        {
            if ($rw == 0) continue;
            
            $username = trim($row[1] ?? '');
            $number   = trim($row[2] ?? '');
            $bank     = trim($row[3] ?? '');

            if (!empty($username) && !empty($number) && !empty($bank)) {
                // Check owner
                $user = User::where('username', $username)->first();
                if (!$user) {
                    throw new \Exception('MESSAGE--User dengan username: "'.$username.'" tidak tersedia!', 1);
                }
                // Check number rekening
                if (BankAccount::where('number', $number)->exists()) {
                    throw new \Exception('MESSAGE--No Rekening: "'.$number.'" sudah digunakan!', 1);
                }
                // Check bank
                if (!in_array($bank, $banks)) {
                    throw new \Exception('MESSAGE--BANK: "'.$bank.'" tidak tersedia!', 1);
                }

                // Save Data
                $record = BankAccount::where('user_id', $user->id)->first();
                if (!$record) {
                    $record = new BankAccount;
                    $record->user_id = $user->id;
                }
                $record->number = $number;
                $record->bank = array_search($bank, $banks);
                $record->save();
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

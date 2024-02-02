<?php

namespace App\Http\Requests\Master\Bank;

use App\Http\Requests\FormRequest;

class BankAccountRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;

        $rules = [
            'user_id' => 'required|unique:ref_bank_accounts,user_id,'.$id,
            'number' => 'required|string|unique:ref_bank_accounts,number,'.$id,
            'bank' => 'required',
        ];

        return $rules;
    }

    public function attributes()
    {
        $attributes = [
            'user_id' => __('Owner'),
            'number' => __('No Rekening'),
            'bank' => __('Bank'),
        ];

        return $attributes;
    }
}

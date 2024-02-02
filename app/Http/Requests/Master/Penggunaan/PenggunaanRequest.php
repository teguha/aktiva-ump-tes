<?php

namespace App\Http\Requests\Master\Penggunaan;

use App\Http\Requests\FormRequest;

class PenggunaanRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;
        $rules = [
            'name'        => 'required|string|max:255|unique:ref_penggunaan,name,'.$id,
        ];

        return $rules;
    }
}
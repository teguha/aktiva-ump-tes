<?php

namespace App\Http\Requests\Master\Pay;

use App\Http\Requests\FormRequest;

class PaymentRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;
        $rules = [
            'Skema Pembayaran'        => 'required|string|max:255|unique:ref_payment,SkemaPembayaran,'.$id,
            'Cara Pembayaran'        => 'required|string|max:255|unique:ref_payment,CaraPembayaran,'.$id,
        ];

        return $rules;
    }
}
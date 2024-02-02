<?php

namespace App\Http\Requests\Sgu;

use Illuminate\Foundation\Http\FormRequest;

class SguRequest extends FormRequest
{
    public function rules()
    {

        $id = $this->route('id') ?? 0;
        $rules = [
            'submission_date' => 'required',
            'work_unit_id' => 'required',
            'code'      => 'required|max:30|unique:trans_sgu,code,'.$id,
        ];

        return $rules;
    }

    public function attributes()
    {
        $attributes = [
            'code' => 'ID Pengajuan SGU',
            'submission_date'  => 'Tgl Pengajuan SGU',
            'work_unit_id' => 'Unit Kerja',
            'payment_scheme' => 'Skema Pembayaran',
        ];

        return $attributes;
    }
}

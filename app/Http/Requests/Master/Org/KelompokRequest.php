<?php

namespace App\Http\Requests\Master\Org;

use App\Http\Requests\FormRequest;

class KelompokRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;
        $rules = [
            'name'      => 'required|string|max:255|unique:ref_kelompok,name,'. $id,
            // 'status'      => 'required',
        ];

        if($id){
            $rules['name'] = 'string|max:255|unique:ref_kelompok,name,'. $id;
        }

        return $rules;
    }
}

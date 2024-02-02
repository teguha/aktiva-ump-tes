<?php

namespace App\Http\Requests\Master\Geo;

use App\Http\Requests\FormRequest;

class ProvRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;
        $rules = [
            'code'        => 'required|string|max:4|unique:ref_province,code,'.$id,
            'name'        => 'required|string|max:32|unique:ref_province,name,'.$id,
        ];

        return $rules;
    }
}

<?php

namespace App\Http\Requests\Master\Geo;

use App\Http\Requests\FormRequest;

class CityRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;
        $rules = [
            'province_id' => 'required',
            'code'        => 'required|string|max:4|unique:ref_city,code,'.$id,
            'name'        => 'required|string|max:32|unique:ref_city,name,'.$id,
        ];

        return $rules;
    }
}

<?php

namespace App\Http\Requests\Master\Geo;

use App\Http\Requests\FormRequest;

class DistrictRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;
        $rules = [
            'city_id'     => 'required',
            'code'        => 'required|string|max:6|unique:ref_district,code,'.$id,
            'name'        => 'required|string|max:32|unique:ref_district,name,'.$id,
        ];

        return $rules;
    }
}

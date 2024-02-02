<?php

namespace App\Http\Requests\Master\Org;

use App\Http\Requests\FormRequest;

class GroupRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;
        $rules = [
            'name'      => 'required|string|max:255|unique:ref_org_structs,name,'.$id.',id,level,group',
            'division' => 'required|array|min:2',
        ];

        return $rules;
    }
}
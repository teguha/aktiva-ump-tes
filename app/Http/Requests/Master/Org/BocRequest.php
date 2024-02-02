<?php

namespace App\Http\Requests\Master\Org;

use App\Http\Requests\FormRequest;

class BocRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;
        $rules = [
            'parent_id' => 'required|exists:ref_org_structs,id',
            'name'      => 'required|string|max:255|unique:ref_org_structs,name,'.$id.',id,level,boc',
        ];

        return $rules;
    }
}
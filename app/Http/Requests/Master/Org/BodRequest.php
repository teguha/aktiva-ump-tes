<?php

namespace App\Http\Requests\Master\Org;

use App\Http\Requests\FormRequest;

class BodRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;
        $rules = [
            'parent_id' => 'required|exists:ref_org_structs,id',
            'name'      => 'required|string|max:255|unique:ref_org_structs,name,'.$id.',id,level,bod',
            // 'status'    => 'required',
        ];

        if($id){
            $rules['parent_id'] = 'exists:ref_org_structs,id';
            $rules['name'] = 'string|max:255|unique:ref_org_structs,name,'.$id.',id,level,bod';
        }

        return $rules;
    }
}
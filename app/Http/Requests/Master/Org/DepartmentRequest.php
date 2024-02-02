<?php

namespace App\Http\Requests\Master\Org;

use App\Http\Requests\FormRequest;

class DepartmentRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;
        $rules = [
            'parent_id' => 'required|exists:ref_org_structs,id',
            'name'      => 'required|string|max:255|unique:ref_org_structs,name,'.$id.',id,level,department',
            // 'status'    => 'required',
        ];

        if($id){
            $rules['parent_id'] = 'exists:ref_org_structs,id';
            $rules['name'] = 'required|string|min:1|max:255|unique:ref_org_structs,name,'.$id.',id,level,department';
        }

        return $rules;
    } 
}
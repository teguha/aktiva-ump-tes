<?php

namespace App\Http\Requests\Master\Org;

use App\Http\Requests\FormRequest;

class SubbranchRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;
        $rules = [
            'parent_id' => 'required|exists:ref_org_structs,id',
            'name'      => 'required|string|max:255|unique:ref_org_structs,name,'. $id.',id,level,subbranch',
            // 'status'    => 'required',
            // 'phone'   => 'required|string|max:20',
            // 'address' => 'required|string|max:65500',
        ];

        if($id){
            $rules['parent_id'] = 'exists:ref_org_structs,id';
            $rules['name'] = 'string|max:255|unique:ref_org_structs,name,'. $id.',id,level,subbranch';
        }
        return $rules;
    }
}

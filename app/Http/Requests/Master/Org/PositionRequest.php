<?php

namespace App\Http\Requests\Master\Org;

use App\Http\Requests\FormRequest;

class PositionRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;
        $loc = $this->location_id ?? 0;
        $rules = [
            'location_id' => 'required|exists:ref_org_structs,id',
            'name'        => 'required|string|max:255|unique:ref_positions,name,null,id,location_id,'.$loc,
            'name'        => 'required|string|max:255',
            'kelompok_id' => 'required|exists:ref_kelompok,id',
            // 'parent_id' => 'required|exists:ref_positions,id',
            // 'status' => 'required'
        ];

        if($id){
            $rules['location_id'] = 'exists:ref_org_structs,id';
            $rules['name']        = 'required|string|max:255';
            $rules['kelompok_id'] = 'exists:ref_kelompok,id';
            // $rules['parent_id'] = 'exists:ref_positions,id';
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'kelompok_id' => 'Kelompok Jabatan'
        ];
    }
}

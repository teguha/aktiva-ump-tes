<?php

namespace App\Http\Requests\Setting\Flow;

use App\Http\Requests\FormRequest;

class FlowRequest extends FormRequest
{
    public function rules()
    {
        $rules = [];
        if (is_array($this->flow)) {
            $rules = [
                'flows' => 'required|array',
                'flows.*.role_id' => 'required|distinct|exists:sys_roles,id',
                'flows.*.type'     => 'required'
            ];
        }

        return $rules;
    }

    public function attributes()
    {
        $attributes = [
            'flows.*.role_id' => __('Role'),
            'flows.*.type' => __('Tipe'),
        ];
        return $attributes;
    }
}

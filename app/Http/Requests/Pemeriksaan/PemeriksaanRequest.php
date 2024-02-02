<?php

namespace App\Http\Requests\Pemeriksaan;

use Illuminate\Foundation\Http\FormRequest;

class PemeriksaanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->record->id ?? 0;
        $rules = [
            'code'              => ['required', 'unique:trans_pemeriksaan,code,'.$id.',id'],
            'date'              => 'required',
            'struct_id'         => 'required',
            'pemeriksa_ids'     => 'required|array',
            'pemeriksa_ids.*'   => 'required',
        ];

        if ($this->is_parent) {
            return $rules;
        }
        return [];
    }
}

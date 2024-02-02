<?php

namespace App\Http\Requests\Pemeriksaan;

use Illuminate\Foundation\Http\FormRequest;

class PemeriksaanDetailRequest extends FormRequest
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
        $detail_id = $this->detail_id ?? 0;
        $rules = [
            'aktiva_id' => 'required|exists:trans_aktiva,id',
            'condition' => 'required',
        ];
        return $rules;
    }
}

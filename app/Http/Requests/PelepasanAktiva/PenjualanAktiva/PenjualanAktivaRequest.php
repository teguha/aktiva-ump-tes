<?php

namespace App\Http\Requests\PelepasanAktiva\PenjualanAktiva;

use Illuminate\Foundation\Http\FormRequest;

class PenjualanAktivaRequest extends FormRequest
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
        $id = $this->route('id') ?? 0;
        $rules = [
            'date' => 'required',
            'struct_id' => 'required',
            'code' => 'required|max:30|unique:trans_penjualan_aktiva,id,'.$id,

        ];

        return $rules;
    }

}

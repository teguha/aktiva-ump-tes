<?php

namespace App\Http\Requests\Master\Jurnal;

use Illuminate\Foundation\Http\FormRequest;

class CoaRequest extends FormRequest
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
            'kode_akun' => 'required|unique:chart_of_accounts,kode_akun,'.$id,
            'nama_akun' => 'required|string|max:255|unique:chart_of_accounts,nama_akun,'.$id,
            'tipe_akun' => 'required',
            // 'status' =>  'required',
        ];

        return $rules;
    }

}

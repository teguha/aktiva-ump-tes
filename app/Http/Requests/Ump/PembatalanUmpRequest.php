<?php

namespace App\Http\Requests\Ump;

use Illuminate\Foundation\Http\FormRequest;

class PembatalanUmpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    // public function authorize()
    // {
    //     return true;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->record->id ?? 0;
        $rules = [
            'id_ump_pembatalan'      => 'required|max:30|unique:trans_ump_pembatalan,id_ump_pembatalan,'.$id,
            'tgl_ump_pembatalan'     => 'required',
            'uraian'  => 'required',

        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'id_pembatalan' => 'ID Pembatalan UMP',
            'tgl_pembatalan' => 'Tanggal Pembatalan UMP',
            'no_surat' => 'No Surat'
        ];
    }

}

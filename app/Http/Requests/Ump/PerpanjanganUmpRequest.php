<?php

namespace App\Http\Requests\Ump;

use Illuminate\Foundation\Http\FormRequest;

class PerpanjanganUmpRequest extends FormRequest
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
            'id_ump_perpanjangan'      => 'required|max:30|unique:trans_ump_perpanjangan,id_ump_perpanjangan,'.$id,
            'tgl_ump_perpanjangan'     => 'required',
            'uraian'  => 'required',

        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'id_perpanjangan' => 'ID Perpanjangan UMP',
            'tgl_perpanjangan' => 'Tanggal Perpanjangan UMP',
            'no_surat' => 'No Surat'
        ];
    }

}

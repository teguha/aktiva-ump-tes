<?php

namespace App\Http\Requests\UMP;

use Illuminate\Foundation\Http\FormRequest;

class PembayaranUMPRequest extends FormRequest
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
            'id_ump_pembayaran'      => 'required|max:30|unique:trans_ump_pembayaran,id_ump_pembayaran,'.$id,
            'tgl_ump_pembayaran'     => 'required',
            'tgl_jatuh_tempo'     => 'required',
            'uraian'  => 'required',

        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'id_ump_pembayaran' => 'ID Pembayaran UMP',
            'tgl_ump_pembayaran' => 'Tanggal Pembayaran UMP',
            'no_surat' => 'No Surat Pembayaran UMP'
        ];
    }

}

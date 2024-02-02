<?php

namespace App\Http\Requests\Ump;

use Illuminate\Foundation\Http\FormRequest;

class PengajuanUmpRequest extends FormRequest
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
            'code_ump'              => 'required|max:30|unique:trans_ump_pengajuan,aktiva_id,'.$id,
            'date_ump'              => 'required',
            // 'tgl_pembayaran'        => 'required',
            'nominal_pembayaran'    => 'required',
            'rekening_id'           => 'required',
            'perihal'               => 'required',
            // 'tgl_jatuh_tempo'       => 'required'

        ];

        return $rules;
    }

    public function attributes()
    {
        return [

        ];
    }

}

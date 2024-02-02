<?php

namespace App\Http\Requests\Ump;

use Illuminate\Foundation\Http\FormRequest;

class PjUmpRequest extends FormRequest
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
            'id_pj_ump'      => 'required|max:30|unique:trans_ump_pj,id_pj_ump,'.$id,
            'tgl_pj_ump'     => 'required',
            'uraian'  => 'required',

        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'id_pj_ump' => 'ID Pengajuan PJ UMP',
            'tgl_pj_ump' => 'Tanggal Pengajuan PJ UMP',
            'no_surat' => 'No Surat Pengajuan PJ UMP'
        ];
    }

}

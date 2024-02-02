<?php

namespace App\Http\Requests\Termin;

use Illuminate\Foundation\Http\FormRequest;

class TerminRequest extends FormRequest
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
            'code' => 'required',
            'date' => 'required',
            'nominal_pembayaran' => 'required',
            'perihal'  => 'required',

        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'no_surat' => 'No Surat',
            'perihal'  => 'Perihal',
            'vendor_id'     => 'Penerima'

        ];
    }

}

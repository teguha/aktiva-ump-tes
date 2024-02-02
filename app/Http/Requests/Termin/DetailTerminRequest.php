<?php

namespace App\Http\Requests\Termin;

use Illuminate\Foundation\Http\FormRequest;

class DetailTerminRequest extends FormRequest
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
            'no_termin' => 'required',
            'nominal'  => 'required|min:2',
            'pajak'     => 'required|min:2'

        ];

        return $rules;
    }

    public function attributes()
    {
        return [
        ];
    }

    public function messages()
    {
        return [
            'min'   => 'Tidak boleh kosong',
        ];
    }

}

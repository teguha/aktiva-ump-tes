<?php

namespace App\Http\Requests\Aktiva;

use Illuminate\Foundation\Http\FormRequest;

class PembelianAktivaRequest extends FormRequest
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
            'struct_id' => 'required',
            'cara_pembayaran' => 'required',
        ];

        return $rules;
    }

}

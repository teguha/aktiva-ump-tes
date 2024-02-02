<?php

namespace App\Http\Requests\MutasiAktiva;

use Illuminate\Foundation\Http\FormRequest;

class PelaksanaanMutasiRequest extends FormRequest
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
            'code' => 'required|unique:trans_pelaksanaan_mutasi_aktiva,code,'.$id,
            'date' => 'required',
        ];
        return $rules;
    }
}

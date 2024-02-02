<?php

namespace App\Http\Requests\MutasiAktiva;

use Illuminate\Foundation\Http\FormRequest;

class MutasiAktivaRequest extends FormRequest
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
        return [
            'code'              => 'required|max:30|unique:trans_mutasi_aktiva,id,'.$id,
            'date'              => 'required',
            'from_struct_id'    => 'required',
            'to_struct_id'      => ['required', 'different:from_struct_id'],
        ];
    }
    function messages() {
        return [
            'to_struct_id.different'    => 'unit asal dan unit tujuan tidak boleh sama',
        ];
    }
}

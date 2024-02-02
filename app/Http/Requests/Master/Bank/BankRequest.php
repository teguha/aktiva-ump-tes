<?php

namespace App\Http\Requests\Master\Bank;

use Illuminate\Foundation\Http\FormRequest;

class BankRequest extends FormRequest
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
            'bank' => 'required|unique:bank,bank,'.$id,
            'alamat' => 'required',
            // 'status' => 'required',
        ];

        return $rules;
    }

}

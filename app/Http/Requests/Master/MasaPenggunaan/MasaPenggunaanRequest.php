<?php

namespace App\Http\Requests\Master\MasaPenggunaan;

use Illuminate\Foundation\Http\FormRequest;

class MasaPenggunaanRequest extends FormRequest
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
            'masa_penggunaan' => 'required|unique:masa_penggunaan,masa_penggunaan,'. $id,
            // 'status' => 'required',
        ];

        return $rules;
    }

}

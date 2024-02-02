<?php

namespace App\Http\Requests\Master\CaraPembayaran;

use Illuminate\Foundation\Http\FormRequest;

class CaraPembayaranRequest extends FormRequest
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
            'nama' => 'required|string|max:75|unique:cara_pembayaran,nama,'.$id,
        ];
    }
}

<?php

namespace App\Http\Requests\Master\JenisPembayaran;

use Illuminate\Foundation\Http\FormRequest;

class JenisPembayaranRequest extends FormRequest
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
            'name' => 'required|string|max:75|unique:jenis_pembayaran,name,'.$id,
        ];
    }
}

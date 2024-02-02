<?php

namespace App\Http\Requests\Master\Barang;

use Illuminate\Foundation\Http\FormRequest;

class VendorRequest extends FormRequest
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
            'id_vendor' => 'required|string|max:255|unique:vendor,name,'.$id,
            'name' => 'required|string',
            'address' => 'required',
            'telp' => 'required',
            'email' => 'required',
            'contact_person' => 'required',

            // 'status' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Vendor',
            'address' => 'Alamat',
            'status' => 'Status',
    
        ];
    }
}

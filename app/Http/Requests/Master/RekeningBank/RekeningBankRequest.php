<?php

namespace App\Http\Requests\Master\RekeningBank;

use Illuminate\Foundation\Http\FormRequest;

class RekeningBankRequest extends FormRequest
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
            'user_id' => 'required|exists:sys_users,id',
            'vendor_id' => 'nullable|exists:vendor,id',
            'no_rekening' => 'required|string|max:25|unique:rekening_bank,no_rekening,'.$id,
            'kcp' => 'required|string|max:250|',
            'bank_id' => 'required|exists:ref_bank,id',
        ];
    }
}

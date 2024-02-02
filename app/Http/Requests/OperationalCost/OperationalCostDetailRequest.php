<?php

namespace App\Http\Requests\OperationalCost;

use Illuminate\Foundation\Http\FormRequest;

class OperationalCostDetailRequest extends FormRequest
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
        $detail_id = $this->detail_id ?? 0;
        $rules = [
            'name' => [
                'required',
                'unique:trans_operationalCost_details,name,' . $detail_id . ',id,pengajuan_id,' . $this->pengajuan_id
            ],
            'cost' => 'required',
            // 'masa_penggunaan' => 'required',
            'tgl_pemesanan' => 'required',
            'vendor_id' => 'required|exists:ref_vendor,id',
            // 'struct_id' => 'required|exists:ref_org_structs,id',
        ];
        return $rules;
    }
}

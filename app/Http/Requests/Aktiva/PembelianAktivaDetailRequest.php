<?php

namespace App\Http\Requests\Aktiva;

use Illuminate\Foundation\Http\FormRequest;

class PembelianAktivaDetailRequest extends FormRequest
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
            'jenis_asset' => 'required',
            'nama_aktiva' => [
                'required',
                'unique:trans_pembelian_aktiva_detail,nama_aktiva,' . $detail_id . ',id,pengajuan_pembelian_id,' . $this->pengajuan_pembelian_id
            ],
            'merk'  => 'required',
            'no_seri' => 'required',
            'jumlah_unit_pembelian' => 'required',
            'harga_per_unit' => 'required',
            'tgl_pembelian' => 'required',
            'vendor_id' => 'required|exists:ref_vendor,id',
            'struct_id' => 'required|exists:ref_org_structs,id',
        ];
        return $rules;
    }
}

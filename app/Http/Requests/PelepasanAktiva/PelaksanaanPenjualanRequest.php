<?php

namespace App\Http\Requests\PelepasanAktiva;

use App\Models\PelepasanAktiva\PelaksanaanPenjualanAktiva;
use Illuminate\Foundation\Http\FormRequest;

class PelaksanaanPenjualanRequest extends FormRequest
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
        $pengajuan_id = $this->pengajuan_id;
        $pelaksanaan = PelaksanaanPenjualanAktiva::firstOrNew(
            [
                'penjualan_aktiva_id' => $pengajuan_id
            ]
        );
        $pelaksanaan->save();
        $rules = [
            'code' => 'required|unique:trans_pelaksanaan_penjualan_aktiva,code,'.$pelaksanaan->id,
            'date' => 'required',
        ];
        return $rules;
    }
}

<?php

namespace App\Http\Requests\Master\MataAnggaran;

use Illuminate\Foundation\Http\FormRequest;

class MataAnggaranRequest extends FormRequest
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
            'mata_anggaran' => 'required|string|max:30|unique:mata_anggaran,mata_anggaran,'.$id,
            'nama' => 'required|string|max:100|unique:mata_anggaran,nama,'.$id,
            // 'status' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'mata_anggaran' => 'Mata Anggaran',
            'nama' => 'Nama Mata Anggaran',
            'status' => 'Status',
        ];
    }
}

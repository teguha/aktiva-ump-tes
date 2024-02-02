<?php

namespace App\Http\Requests\PelepasanAktiva\PenghapusanAktiva;

use Illuminate\Foundation\Http\FormRequest;

class PenghapusanAktivaDetailSubmitRequest extends FormRequest
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
        $id = $this->route('id') ?? 0;
        $rules = [
            'description' => 'required',
        ];

        return $rules;
    }

}

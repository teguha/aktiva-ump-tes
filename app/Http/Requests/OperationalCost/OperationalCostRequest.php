<?php

namespace App\Http\Requests\OperationalCost;

use Illuminate\Foundation\Http\FormRequest;

class OperationalCostRequest extends FormRequest
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
            'code' => 'required|max:30|unique:trans_operationalcost,id,'.$id,
            'date' => 'required',
            'struct_id' => 'required',
            'cara_pembayaran' => 'required',
        ];

        return $rules;
    }

}

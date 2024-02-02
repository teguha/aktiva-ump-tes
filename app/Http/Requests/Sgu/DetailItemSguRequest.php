<?php

namespace App\Http\Requests\Sgu;

use Illuminate\Foundation\Http\FormRequest;

class DetailItemSguRequest extends FormRequest
{
    public function rules()
    {

        $id = $this->record->id ?? 0;
        $rules = [
            'rent_location' => 'required|max:100',
            'rent_start_date' => 'required',
            'rent_time_period' => 'required',
            'deposit' => 'required',
            'rent_cost' => 'required',
            'payment_date' => 'required',
            'sentence_start' => 'required',
            'sentence_end' => 'required',
        ];

        return $rules;
    }

}

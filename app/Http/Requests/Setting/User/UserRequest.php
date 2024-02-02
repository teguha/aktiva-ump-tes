<?php

namespace App\Http\Requests\Setting\User;

use App\Http\Requests\FormRequest;

class UserRequest extends FormRequest
{
    public function rules()
    {
        $isAdmin = $this?->record?->username == "admin";
        $id = $this->record->id ?? 0;
        $rules = [
            'name'        => 'required|max:255',
            'phone'       => 'required',
            // 'rekening.*.bank' => 'sometimes|exists:ref_bank,id',
            // 'rekening.*.no_rekening' => 'required_unless:rekening.*.bank,null',
            // 'rekening.*.status' => 'required_unless:rekening.*.bank,null',

        ];
        if (!$isAdmin) {
            $rules += [
                'email'       => 'required|max:60|email|unique:sys_users,email,' . $id,
                'status'      => 'required',
                'nik'         => 'required|unique:sys_users,nik,' . $id,
                'position_id' => 'required|exists:ref_positions,id',
                'location_id' => 'required|exists:ref_org_structs,id',
                'roles.*'       => 'required',
            ];
        }
        if (!$id) {
            $rules += [
                'username'              => 'required|string|max:60|unique:sys_users,username,' . $id,
                'password'              => 'required|confirmed',
                'password_confirmation' => 'required',
            ];
        }
        return $rules;
    }

    public function attributes()
    {
        return [
            'rekening.*.bank_id' => 'Bank',
            'rekening.*.no_rekening' => 'No Rekening',
            'rekening.*.status' => 'Status',
            'phone' => 'No Handphone'
        ];
    }
}

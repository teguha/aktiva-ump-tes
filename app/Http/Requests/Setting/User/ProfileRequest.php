<?php

namespace App\Http\Requests\Setting\User;

use App\Http\Requests\FormRequest;

class ProfileRequest extends FormRequest
{
    public function rules()
    {
        $roles = [
            'email'       => 'required|email|unique:sys_users,email,'.$this->id,
        ];
        return $roles;
    }

    public function customAttributes()
    {
        return [
            'email' => 'Email',
        ];
    }
}

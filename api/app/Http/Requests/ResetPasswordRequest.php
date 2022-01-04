<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestTrait;

class ResetPasswordRequest extends FormRequest
{
    use RequestTrait;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "email" => "required|email|exists:users,email",
            "token" => "required|exists:users,remember_token",
            "password" => "required|min:8|confirmed",
            "password_confirmation" => "required|min:8",
        ];
    }
}

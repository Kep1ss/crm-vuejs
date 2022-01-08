<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestTrait;

class LoginRequest extends FormRequest
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
            "email" => "required|max:50",
            "password" => "required|min:8"
        ];
    }
}

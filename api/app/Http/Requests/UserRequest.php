<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestTrait;

class UserRequest extends FormRequest
{
    use RequestTrait;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            "username" => "required|max:255|unique:users",
            "fullname" => "nullable|max:255",
            "email" => "required|max:255|unique:users",
            "password" => "required|min:8",
            "role" => "nullable|integer"
        ];

        if($this->method() == "PUT" || $this->method() == "put"){
            $rules["email"] = $rules["email"].",email,".$this->user->id;
            $rules["username"] = $rules["username"].",username,".$this->user->id;
            $rules["password"] = "nullable|min:8";            
        }

        return $rules;
    }
}

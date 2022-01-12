<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestTrait;
use App\Models\User;

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
            "rules" => "nullable"
        ];

        switch (auth()->user()->role) {            
            case User::ROLE_SUPERADMIN:
                $rules["role"] = "required|integer|in:".User::ROLE_MANAGER_NASIONAL.",".User::ROLE_KOTELE;
                break;                       
        }    
            
        if($this->method() == "PUT" || $this->method() == "put"){
            $rules["email"] = $rules["email"].",email,".$this->user->id;
            $rules["username"] = $rules["username"].",username,".$this->user->id;
            $rules["password"] = "nullable|min:8";
            unset($rules["role"]);
        }

        return $rules;
    }
}

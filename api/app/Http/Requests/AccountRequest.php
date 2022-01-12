<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestTrait;
use App\Models\User;

class AccountRequest extends FormRequest
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
            "role" => "nullable",
        ];

        switch (auth()->user()->role) {                     
            case User::ROLE_MANAGER_NASIONAL:
                $rules["role"] = "required|integer|in:".User::ROLE_MANAGER_AREA;
                break;                        
            case User::ROLE_MANAGER_AREA:
                $rules["role"] = "required|integer|in:".User::ROLE_KAPER;
                break;        
            case User::ROLE_KAPER:
                $rules["role"] = "required|integer|in:".User::ROLE_SPV;
                break;            
            case User::ROLE_SPV:
                $rules["role"] = "required|integer|in:".User::ROLE_SALES;
                break;            
            case User::ROLE_KOTELE:
                $rules["role"] = "required|integer|in:".User::ROLE_TELE_MARKETING;
                break;                
        }
    
        if($this->method() == "PUT" || $this->method() == "put"){
            $rules["email"] = $rules["email"].",email,".$this->account->id;
            $rules["username"] = $rules["username"].",username,".$this->account->id;
            $rules["password"] = "nullable|min:8";
            unset($rules["role"]);
        }

        return $rules;
    }
}

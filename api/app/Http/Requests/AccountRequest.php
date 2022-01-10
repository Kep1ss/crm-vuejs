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
            "role" => "required|integer"
        ];

        $role = "";

        switch (auth()->user()->role) {            
            case User::ROLE_SUPERADMIN:
                $role = "|in:".User::ROLE_MANAGER_NASIONAL.",".User::ROLE_KOTELE;
                break;            
            case User::ROLE_MANAGER_NASIONAL:
                $role = "|in:".User::ROLE_ADMIN_NASIONAL.",".User::ROLE_MANAGER_AREA;
                break;                        
            case User::ROLE_MANAGER_AREA:
                $role = "|in:".User::ROLE_ADMIN_AREA.",".User::ROLE_KAPER;
                break;        
            case User::ROLE_KAPER:
                $role = "|in:".User::ROLE_ADMIN_KAPER.",".User::ROLE_SPV;
                break;            
            case User::ROLE_SPV:
                $role = "|in:".User::ROLE_SALES;
                break;            
            case User::ROLE_KOTELE:
                $role = "|in:".User::ROLE_TELE_MARKETING;
                break;    
            case User::ROLE_ADMIN_NASIONAL:
                $role = "|in:".User::ROLE_MANAGER_AREA;
                break;            
            case User::ROLE_ADMIN_AREA:
                $role = "|in:".User::ROLE_KAPER;
                break;            
            case User::ROLE_ADMIN_KAPER:
                $role = "|in:".User::ROLE_SPV;
                break;
        }

        $rules["role"] = $rules["role"].$role;

        if($this->method() == "PUT" || $this->method() == "put"){
            $rules["email"] = $rules["email"].",email,".$this->account->id;
            $rules["username"] = $rules["username"].",username,".$this->account->id;
            $rules["password"] = "nullable|min:8";
            unset($rules["role"]);
        }

        return $rules;
    }
}

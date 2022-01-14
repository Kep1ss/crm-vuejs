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
            "id_area" => "nullable",

        ];

        switch (auth()->user()->role) {
            case User::ROLE_MANAGER_NASIONAL:
                $rules["role"] = "required|integer|in:".User::ROLE_MANAGER_AREA;
                $rules["target_copies"] = "required|integer";
                break;
            case User::ROLE_MANAGER_AREA:
                $rules["role"] = "required|integer|in:".User::ROLE_KAPER;
                $rules["target_copies"] = "required|integer";
                break;
            case User::ROLE_KAPER:
                $rules["role"] = "required|integer|in:".User::ROLE_SPV;
                $rules["target_copies"] = "required|integer";
                break;
            case User::ROLE_SPV:
                $rules["role"] = "required|integer|in:".User::ROLE_SALES;
                $rules["target_copies"] = "required|integer";
                break;
            case User::ROLE_KOTELE:
                $rules["role"] = "required|integer|in:".User::ROLE_TELE_MARKETING;
                break;
        }

        if($this->method() == "PUT" || $this->method() == "put"){
            switch (auth()->user()->role){
               case User::ROLE_MANAGER_NASIONAL:
                    $rules["email"] = $rules["email"].",email,".$this->account->id;
                    $rules["username"] = $rules["username"].",username,".$this->account->id;
                    break;
               case User::ROLE_MANAGER_AREA:
                    $rules["email"] = $rules["email"].",email,".$this->account->id;
                    $rules["username"] = $rules["username"].",username,".$this->account->id;
                    break;
               case User::ROLE_KAPER:
                    $rules["email"] = $rules["email"].",email,".$this->account->id;
                    $rules["username"] = $rules["username"].",username,".$this->account->id;
                    break;
               case User::ROLE_SPV:
                    $rules["email"] = $rules["email"].",email,".$this->account->id;
                    $rules["username"] = $rules["username"].",username,".$this->account->id;
                    break;
               case User::ROLE_KOTELE:
                    $rules["email"] = $rules["email"].",email,".$this->account->id;
                    $rules["username"] = $rules["username"].",username,".$this->account->id;
                    break;
                default :
                    $rules["email"] = $rules["email"].",email,".$this->account->id;
                    $rules["username"] = $rules["username"].",username,".$this->account->id;
            }
            $rules["password"] = "nullable|min:8";
            unset($rules["role"]);
        }

        return $rules;
    }
}

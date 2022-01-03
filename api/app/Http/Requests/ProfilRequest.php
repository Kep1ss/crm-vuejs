<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestTrait;

class ProfilRequest extends FormRequest
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
            "username" => "required|max:255",
            "fullname" => "nullable|max:200",
            "email" => "required|unique:db_users.users,email,".auth()->user()->id."|max:50",
        ];                    
    }
}

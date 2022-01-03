<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestTrait;

class FingerDeviceRequest extends FormRequest
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
            "name" => "required|max:100",            
            "address" => "required|max:200"
        ];

        return $rules;
    }
}

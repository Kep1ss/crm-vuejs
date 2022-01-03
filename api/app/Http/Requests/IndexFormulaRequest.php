<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestTrait;

class IndexFormulaRequest extends FormRequest
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
            "name" => "required|max:50",            
            "value" => "required|numeric|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/"
        ];

        return $rules;
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestTrait;

class PositionRequest extends FormRequest
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
            "code" => "required|max:25|unique:positions",
            "name" => "required|max:100"
        ];

        if($this->method() == "PUT" || $this->method() == "put"){
            $rules["code"] = $rules["code"].",code,".$this->position->id;
        }

        return $rules;
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestTrait;

class PayrollParameterRequest extends FormRequest
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
            "name" => "required|max:200",
            "parameter_type" => "nullable|in:Tunjangan,Potongan,Lain-Lain"
        ];

        return $rules;
    }
}

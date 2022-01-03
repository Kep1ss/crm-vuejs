<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestTrait;

class PayrollParameterFormulaRequest extends FormRequest
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
            "payroll_parameter_id" => "required|integer|exists:payroll_parameters,id",        
            "formula" => "required|string"
        ];

        return $rules;
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestTrait;

class EmployePayrollParameterRequest extends FormRequest
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
            "payroll_parameter_id" => "required|integer|exists:payroll_parameters,id",
            "employe_id" => "required|integer|exists:employes,id",
            "payroll_method" => "nullable|in:Tetap,Harian,Bulanan",
            "workday" => "required|integer",
            "percentage" => "nullable|numeric|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/",
            "amount" => "nullable|numeric|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/",
        ];
    }
}

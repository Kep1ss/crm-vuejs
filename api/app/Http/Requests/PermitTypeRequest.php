<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestTrait;

class PermitTypeRequest extends FormRequest
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
            "name" => "required|max:200",
            "permit_formulas" => "required|array",
            "permit_formulas.*.payroll_parameter_id" => "required|integer|exists:payroll_parameters,id",
            "permit_formulas.*.percent" => "required|numeric|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/",
            "permit_formulas.*.nominal" => "required|numeric|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/",        
        ];
    }
}

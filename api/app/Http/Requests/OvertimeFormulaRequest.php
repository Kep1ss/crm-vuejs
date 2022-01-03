<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestTrait;

class OvertimeFormulaRequest extends FormRequest
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
            "overtime_category_id" => "required|integer|exists:overtime_categories,id",
            "index_formula_id" => "required|integer|exists:index_formulas,id",
            "formula" => "required|string"
        ];

        return $rules;
    }
}

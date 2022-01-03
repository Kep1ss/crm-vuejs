<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestTrait;

class PermitEmployeRequest extends FormRequest
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
            "employe_id" => "required|integer|exists:employes,id",
            "permit_type_id" => "required|integer|exists:permit_types,id",
            "description" => "nullable|max:200",
            "permit_date_start" => "nullable|date_format:Y-m-d",
            "permit_date_end" => "nullable|date_format:Y-m-d",
            "days_permit" => "required|integer",
        ];
    }
}

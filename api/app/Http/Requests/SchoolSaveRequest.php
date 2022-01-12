<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestTrait;

class SchoolSaveRequest extends FormRequest
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
            "district_id" => "required|exists:districts,id",
            "schools" => "required|array",
            "schools.*.code" => "required|string",
            "schools.*.is_private" => "required|integer|in:1,0",
            "schools.*.level" => "required|in:TK,SD,SMP,SMK,SMA,SLB",
            "schools.*.name" => "required",
            "schools.*.member" => "nullable|integer"
        ];
    }
}

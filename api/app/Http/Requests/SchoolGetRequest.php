<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestTrait;

class SchoolGetRequest extends FormRequest
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
            "province_id" => "required|exists:provinces,id",
            "city_id" => "required|exists:cities,id",
            "district_id" => "required|exists:districts,id",
            "level" => "required|in:TK,SD,SMP,SMK,SMA,SLB",
            "year" => "required|integer",
            "semester" => "required|integer|in:1,2"
        ];
    }
}

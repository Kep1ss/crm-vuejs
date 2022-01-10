<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestTrait;

class SchoolRequest extends FormRequest
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
            "name" => "required|max:50",
            "district_id" => "required|integer|exists:districts,id",
            "member" => "required|integer",
            "level" => "required|in:TK,SD,SMP,SMA,SMK,SLB",
            "is_private" => "required|integer|in:0,1",
            "address" => "nullable",
            "phone_headmaster" => "nullable|max:20",
            "phone_teacher" => "nullable|max:20",
            "phone_treasurer" => "nullable|max:20"
        ];
    }
}

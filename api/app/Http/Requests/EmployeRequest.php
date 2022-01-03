<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestTrait;

class EmployeRequest extends FormRequest
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
            "code" => "required|max:16|unique:employes",
            "citizen_id" => "required|max:16",
            "name" => "required|max:200",
            "gender" => "nullable|in:L,P",
            "address" => "nullable|max:200",
            "city" => "nullable|max:25",
            "birth_day" => "required|date_format:Y-m-d",
            "graduate" => "nullable|max:30",
            "phone" => "nullable|max:18",
            "division_id" => "required|integer|exists:divisions,id",
            "position_id" => "required|integer|exists:positions,id",
            "join_date" => "nullable|date_format:Y-m-d",
            "resigned" => "nullable|date_format:Y-m-d",
            "npwp" => "required|max:20",
            "salary" => "required|gt:0|numeric|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/"
        ];

        if($this->method() == "PUT" || $this->method() == "put"){
            $rules["code"] = $rules["code"].",code,".$this->employe->id;
        }

        return $rules;
    }
}

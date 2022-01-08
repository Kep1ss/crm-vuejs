<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestTrait;

class SettingLogoRequest extends FormRequest
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
            "logo" => "required|image|mimes:jpeg,png,jpg|max:10024|dimensions:max_width=5000,max_height=5000"
        ];
    }
}

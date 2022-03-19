<?php

namespace App\Http\Requests\Base\Line;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

class RefreshRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $current_route = Route::currentRouteName();
        $method = strtoupper($this->getMethod());
        $rules = [];

        if ($method === "POST") {
            if ($current_route === "api.line.refresh.index") {
                $rules = [
                    "api_token" => [
                        "required",
                        "string",
                        Rule::exists("line_members", "api_token"),
                    ]
                ];
            }
        }


        return $rules;
    }



    public function validationDate()
    {
        $validation_data = array_merge(
            $this->all(),
            $this->input(),
            $this->route()->parameters()
        );

        return $validation_data;
    }
}

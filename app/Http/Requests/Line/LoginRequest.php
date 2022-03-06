<?php

namespace App\Http\Requests\Line;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class LoginRequest extends FormRequest
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
        $rules = [];

        $current_route = Route::currentRouteName();
        if ($this->isMethod("get")) {
            if ($current_route === "line.login.index") {
                $rules = [
                    "line_account_id" => [
                        "required",
                        "integer",
                    ]
                ];
            }
        }

        return $rules;
    }


    public function validationData()
    {
        return array_merge(
            $this->all(),
            $this->route()->parameters(),
        );
    }
}

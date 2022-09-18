<?php

namespace App\Http\Requests\Base\Line;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;

class CallbackRequest extends FormRequest
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
            if ($current_route === "line.callback.index") {
                $rules = [
                    "line_account_id" => [
                        "required",
                        "integer",
                    ],
                    "code" => [
                        "required",
                        "string",
                    ],
                    "state" => [
                        "required",
                        "string",
                    ],
                    "api_token" => [
                        "required",
                        "string",
                    ],
                ];
            } elseif ($current_route === "line.callback.completed") {
                $rules = [
                    "line_account_id" => [
                        "required",
                        "integer",
                    ],
                    // "api_token" => [
                    //     "required",
                    //     "string",
                    // ],
                    // api_tokenの代わりにjwtパラメータを渡す
                    "jwt" => [
                        "required",
                        "string",
                    ],
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

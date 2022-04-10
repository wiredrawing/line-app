<?php

namespace App\Http\Requests\Admin\Base\Line;

use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
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
        $method = strtoupper($this->getMethod());
        $route_name = Route::currentRouteName();
        $rules = [];

        if ($method === "POST") {
        } elseif ($method === "GET") {
            if ($route_name === "admin.line.account.index") {
            } elseif ($route_name === "admin.line.account.detail") {
                $rules = [
                    "line_account_id" => [
                        "required",
                        "integer",
                        Rule::exists("line_accounts", "id"),
                    ],
                ];
            }
        }


        return $rules;
    }

    /**
     * validate対象のデータ
     *
     * @return array
     */
    public function validationData():array
    {
        $validation_data = array_merge(
            $this->all(),
            $this->input(),
            $this->route()->parameters(),
        );
        return $validation_data;
    }
}

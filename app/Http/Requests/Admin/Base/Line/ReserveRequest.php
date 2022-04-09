<?php

namespace App\Http\Requests\Admin\Base\Line;

use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * 管理画面向けReserveRequest
 *
 */
class ReserveRequest extends FormRequest
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


        if ($method === "GET") {
            if ($route_name === "admin.line.reserve.index") {
            } elseif ($route_name === "admin.line.reserve.detail") {
                $rules = [
                    "line_reserve_id" => [
                        "required",
                        "integer",
                        Rule::exists("line_reserves", "id"),
                    ]
                ];
            } elseif ($route_name === "admin.line.reserve.sent") {
            }
        }


        return $rules;
    }



    /**
     * バリデーションの対象となる値
     *
     * @return array
     */
    public function validationData():array
    {
        $validation_data = array_merge(
            $this->all(),
            $this->input(),
            $this->route()->parameters()
        );
        return $validation_data;
    }
}

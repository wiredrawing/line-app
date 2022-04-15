<?php

namespace App\Http\Requests\Admin\Base\Line;

use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class MemberRequest extends FormRequest
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
    public function rules(): array
    {
        logger()->info($this->validationData());

        $rules = [];
        $route_name = Route::currentRouteName();

        if ($this->isMethod("post")) {

        } else if ($this->isMethod("get")) {

            if ($route_name === "admin.api.line.member.detail") {
                $rules = [
                    "line_member_id" => [
                        "required",
                        "integer",
                        Rule::exists("line_members", "id"),
                    ]
                ];
            } else if ($route_name === "admin.api.line.member.list") {

            }
        }

        return $rules;
    }


    /**
     * @return array
     */
    public function validationData():array
    {
        return array_merge(
            $this->all(),
            $this->input(),
            $this->route()->parameters()
        );
    }
}

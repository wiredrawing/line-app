<?php

namespace App\Http\Requests\Front\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Route;

class FollowerRequest extends FormRequest
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
        $route_name = Route::currentRouteName();

        if ($this->isMethod("get")) {
            if ($route_name === "front.api.top.follower.followed") {
                $rules = [
                    "player_id" => [
                        "required",
                        "integer",
                    ],
                ];
            } else if ($route_name === "front.api.top.follower.following") {
                $rules = [
                    "player_id" => [
                        "required",
                        "integer",
                    ],
                ];
            } else if ($route_name === "front.api.top.follower.matched") {
                $rules = [
                    "player_id" => [
                        "required",
                        "integer",
                    ],
                ];
            }

        } else if ($this->isMethod("post")) {

            if ($route_name === "front.api.top.follower.create") {
                // playerのフォロー処理
                $rules = [
                    "from_player_id" => [
                        "required",
                        "integer",
                    ],
                    "to_player_id" => [
                        "required",
                        "integer",
                    ],
                ];
            } else if ($route_name === "front.api.top.follower.unfollow") {
                // playerのフォロー解除処理
                $rules = [
                    "from_player_id" => [
                        "required",
                        "integer",
                    ],
                    "to_player_id" => [
                        "required",
                        "integer",
                    ],
                ];
            }
        }

        return $rules;
    }


    /**
     * @return array|mixed
     */
    public function validationData()
    {
        return array_merge($this->input(), $this->route()
            ->parameters(), $this->all(),);
    }

    /**
     * API実行時エラーをapplication/jsonで返却する
     *
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()
            ->toArray();
        logger()->error($errors);
        $response = [
            "status" => false,
            "response" => null,
            "errors" => $errors,
        ];
        throw new HttpResponseException(response()->json($response), 422);
    }
}

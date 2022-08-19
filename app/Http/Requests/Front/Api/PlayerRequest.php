<?php

namespace App\Http\Requests\Front\Api;

use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PlayerRequest extends FormRequest
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
        $rules = [];
        $route_name = Route::currentRouteName();
        if ($this->isMethod("get")) {
            if ($route_name === "front.api.top.player.detail") {
                // 指定したplayer_idかつプレイヤーが公開中のプレイヤー情報を取得する
                $rules = [
                    "player_id" => [
                        "required",
                        "integer",
                    ]
                ];
            } else if ($route_name === "front.api.top.player.list") {

            } else if ($route_name === "front.api.top.player.search") {

            }
        } else if ($this->isMethod("post")) {
            if ($route_name === "front.api.to.player.update") {
                $rules = [
                    "family_name" => [
                        "nullable",
                        "string",
                    ],
                    "middle_name" => [
                        "nullable",
                        "string",
                    ],
                    "given_name" => [
                        "nullable",
                        "string",
                    ],
                    "nickname" => [
                        "nullable",
                        "string",
                    ],
                    "gender_id" => [
                        "nullable",
                        "integer",
                    ],
                    "is_published" => [
                        "nullable",
                        "integer",
                    ],
                ];
            }
        }

        // トークンはどのリクエストでも必須とする
        $rules["api_token"] = [
            "required",
            "string",
            "min:1,max:2046",
        ];
        return $rules;
    }

    /**
     * @return array
     */
    public function validationData(): array
    {
        return array_merge($this->input(), $this->route()
            ->parameters(), $this->all());
    }
}

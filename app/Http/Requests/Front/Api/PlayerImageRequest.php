<?php

namespace App\Http\Requests\Front\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

class PlayerImageRequest extends FormRequest
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
        $route_name = Route::currentRouteName();
        $rules = [];

        switch (strtoupper($this->method())) {
            case "GET":
                // resourceの取得のみ
                if ($route_name === "front.api.top.player.image.list") {
                    $rules = [
                        "player_id" => [
                            "required",
                            "integer",
                            Rule::exists("players", "id"),
                        ]
                    ];
                }
                break;

            case "POST":
                // resourceの更新
                if ($route_name === "front.api.top.player.image.create") {

                    $rules = [
                        "image_id" => [
                            "required",
                            "string",
                            Rule::exists("images", "id"),
                        ],
                        "player_id" => [
                            "required",
                            "integer",
                            Rule::exists("players", "id"),
                        ]
                    ];
                } else if ($route_name === "front.api.top.player.image.delete") {
                    $rules = [
                        "id" => [
                            "required",
                            "string",
                            Rule::exists("player_images", "id"),
                        ]
                    ];
                }
                break;
            default:
                break;
        }

        return $rules;
    }

    /**
     * @return array
     */
    public function validationData(): array
    {
        return array_merge(
            $this->input(),
            $this->route()->parameters(),
            $this->all()
        );
    }

    /**
     * API実行時エラーをapplication/jsonで返却する
     *
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->toArray();
        logger()->error($errors);
        $response = [
            "status" => false,
            "response" => null,
            "errors" => $errors,
        ];
        throw new HttpResponseException(response()->json($response));
    }
}

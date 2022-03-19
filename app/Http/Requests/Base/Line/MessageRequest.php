<?php

namespace App\Http\Requests\Base\Line;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class MessageRequest extends FormRequest
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

        $rules = [];
        if ($this->isMethod("post")) {
            if ($current_route === "api.line.message.reserve") {
                $rules = [
                    "line_account_id" => [
                        "required",
                        "integer",
                    ],
                    "messages" => [
                        "required",
                        "array",
                        "between:1,5"
                    ],
                    "messages.*.type" => [
                        "required",
                        "string",
                    ],
                    "messages.*.text" => [
                        "required",
                        "string",
                        "between:1,4000"
                    ],
                    // 配信予定日は日時を厳格に指定するようにする
                    "delivery_datetime" => [
                        "required",
                        "date_format:Y-m-d H:i",
                    ]
                ];
            } elseif ($current_route === "api.line.message.pushing") {
                $rules = [
                    "line_reserve_id" => [
                        "required",
                        "integer",
                    ],
                ];
            }
        } elseif ($this->isMethod("get")) {

            // 未送信のメッセージ一覧を取得する
            if ($current_route === "api.line.message.unsent") {
                $rules = [
                    "line_account_id" => [
                        "required",
                        "integer",
                    ]
                ];
            } elseif ($current_route === "api.line.message.sent") {
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

<?php

namespace App\Http\Requests\Admin\Api\Line;

use App\Models\LineAccount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

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
        $current_route = Route::currentRouteName();

        $rules = [];
        if ($this->isMethod("post")) {
            if ($current_route === "admin.api.line.reserve.reserve") {
                $rules = [
                    "line_account_id" => [
                        "required",
                        "integer",
                    ],
                    "api_token" => [
                        "required",
                        "string",
                        function ($attribute, $value, $fail) {
                            // ----------------------------------------------------
                            // line_account_idとline_accounts.api_tokenの
                            // 組み合わせを検証する
                            // ----------------------------------------------------
                            $line_account = LineAccount::where([
                                "api_token" => $value,
                                "is_enabled" => Config("const.binary_type.on"),
                            ])
                            ->find($this->route()->parameter("line_account_id"));
                            if ($line_account === null) {
                                $fail("Could not find record which specified.");
                            }
                        }
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
            } elseif ($current_route === "admin.api.line.reserve.push") {
                $rules = [
                    // 配信したいline_reservesのid
                    "line_reserve_id" => [
                        "required",
                        "integer",
                    ],
                    // 配信対象の予約データの所有公式アカウントに紐づくapi_token
                    "api_token" => [
                        "required",
                        "string",
                    ]
                ];
            }
        } elseif ($this->isMethod("get")) {

            // 未送信のメッセージ一覧を取得する
            if ($current_route === "admin.api.line.reserve.unsent") {
                $rules = [
                    "line_account_id" => [
                        "required",
                        "integer",
                    ]
                ];
            } elseif ($current_route === "admin.api.line.reserve.sent") {
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


    /**
     * バリデーション対象のデータをここで構築する
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
        logger()->info($validation_data);
        return $validation_data;
    }
}

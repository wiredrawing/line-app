<?php

namespace App\Http\Requests\Admin\Base\Line;

use App\Models\LineAccount;
use Carbon\Carbon;
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

        $route_name = Route::currentRouteName();
        $rules = [];


        if ($this->isMethod("get")) {

            // API系へのリクエストの場合
            if (Route::is("admin.api.*")) {
                if ($route_name === "admin.api.line.reserve.unsent") {
                    // ルールを策定
                } elseif ($route_name === "admin.api.line.reserve.sent") {
                    // ルールを策定
                } elseif ($route_name === "admin.api.line.reserve.fetchReserve") {
                    // ルールを策定
                }
                // 共通ルールを定義
                $rules["line_account_id"] = [
                    "required",
                    "integer",
                    function ($attribute, $value, $fail) {
                        $line_account = LineAccount::find($value);
                        if ($line_account === null) {
                            return $fail("Could not find line account which you selected.");
                        }
                        return  true;
                    }
                ];
            } else {
                if ($route_name === "admin.line.reserve.index") {
                    // -----------------------------------------------------
                    // 現在有効なLINEメッセージ予約一覧を取得する
                    // -----------------------------------------------------
                } elseif ($route_name === "admin.line.reserve.detail") {
                    // -----------------------------------------------------
                    // 任意の指定されたLINEメッセージ予約を取得し表示する
                    // -----------------------------------------------------
                    $rules = [
                        "line_reserve_id" => [
                            "required",
                            "integer",
                            Rule::exists("line_reserves", "id"),
                        ]
                    ];
                } elseif ($route_name === "admin.line.reserve.sent") {
                    // -----------------------------------------------------
                    // リクエスト時点ですでに送信済みの予約一覧を取得
                    // -----------------------------------------------------
                } elseif ($route_name === "admin.line.reserve.register") {
                    // -----------------------------------------------------
                    // 新規のLINEメッセージ予約画面
                    // メッセージの登録自体はAPI側に処理を投げる
                    // -----------------------------------------------------
                }
            }

        } else if($this->isMethod("post")) {
            if ($route_name === "admin.api.line.reserve.reserve") {
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
                                "is_displayed" => Config("const.binary_type.on"),
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
                        Rule::in(Config("const.line_message_types"))
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
                        function ($attribute, $value, $fail) {
                            // 配信予定日は最速で1時間後から許可する
                            $current = Carbon::now("Asia/Tokyo");
                            $carbon = new Carbon($value);
                            $diff = $carbon->getTimestamp() - $current->getTimestamp();
                            if ($diff <= 60 * 60) {
                                $fail("配信予定日は最速一時間後から可能となります");
                            }
                        }
                    ]
                ];
            } elseif ($route_name === "admin.api.line.reserve.push") {
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
            $this->input(),
            $this->route()->parameters(),
            $this->all(),
        );
        // リクエスト内容のロギング
        logger()->info("validation_data ===> ", $validation_data);
        return $validation_data;
    }
}

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
                // -----------------------------------------------------
                // 現在有効なLINEメッセージ予約一覧を取得する
                // -----------------------------------------------------
            } elseif ($route_name === "admin.line.reserve.detail") {
                // -----------------------------------------------------
                // 任意の指定されたLINEメッセージ予約を取得し表示すうr
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

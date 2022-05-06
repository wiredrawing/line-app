<?php

namespace App\Http\Requests\Admin\Base;

use App\Models\PasswordReset;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class PasswordRequest extends FormRequest
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

        if ($this->isMethod("post")) {
            if ($route_name === "admin.password.postRenew") {
                $rules = [
                    "password" => [
                        "required",
                        "string",
                        "min:9",
                        "max:72"
                    ]
                ];
            }
        } else if ($this->isMethod("get")) {

            if ($route_name === "admin.password.renew") {
                // パスワードリセットリンク再発行フォーム
            } else if ($route_name === "admin.password.completed") {
                // パスワードリセットリンク再発行完了画面
            } else if ($route_name === "admin.password.reset") {
                // 新規パスワード入力画面
                $rules = [
                    "token" => [
                        "required",
                        "string",
                    ],
                ];
            }
        }
        return $rules;
    }

    /**
     * バリデーション対象のデータを作成
     *
     * @return array
     */
    public function validationData(): array
    {
        return array_merge(
            $this->all(),
            $this->input(),
            $this->route()->parameters(),
        );
    }
}

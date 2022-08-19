<?php

namespace App\Http\Requests\Front\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class GameTitleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
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

            if ($route_name === "front.api.top.gameTitle.list") {
                // 現在,有効なゲームタイトル一覧を返却
            }
        } else if ($this->isMethod("post")) {
            if ($route_name === "front.api.top.gameTitle.create") {
                // 新規のゲームタイトル追加
            } else if ($route_name === "front.api.top.gameTitle.update") {
                // 既存の登録ずみゲームタイトルの編集処理
            }
        }

        return $rules;
    }
}

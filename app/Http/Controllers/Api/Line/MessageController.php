<?php

namespace App\Http\Controllers\Api\Line;

use App\Http\Controllers\Controller;
use App\Http\Requests\Base\Line\MessageRequest;
use App\Models\LineMember;
use App\Models\LineMessage;
use Illuminate\Support\Facades\Http;

class MessageController extends Controller
{


    /**
     * 指定したLINEアカウントでメッセージを予約する
     *
     * @param MessageRequest $request
     * @param integer $line_account_id
     * @return void
     */
    public function reserve(MessageRequest $request, int $line_account_id)
    {
        try {
            $validated = $request->validated();

            $insert_messages = [];

            foreach ($validated["messages"] as $key => $value) {
                $insert_messages[] = [
                    "line_account_id" => $validated["line_account_id"],
                    "delivery_datetime" => $validated["delivery_datetime"],
                    "type" => $value["type"],
                    "text" => $value["text"],
                    "created_at" => date("Y-m-d H:i:s"),
                    "updated_at" => date("Y-m-d H:i:s"),
                ];
            }

            $response = LineMessage::insert($insert_messages);

            if ($response !== true) {
                throw new \Exception("メッセージの予約に失敗しました");
            }
        } catch (\Exception $e) {
            return view("errors.index", [
                "e" => $e,
            ]);
        }
    }


    /**
     * 指定したlineユーザーに指定したメッセージをPush通知する
     *
     * @param MessageRequest $request
     * @param integer $line_account_id
     * @return void
     */
    public function pushing(MessageRequest $request, int $line_account_id)
    {
        try {
            $validated = $request->validated();

            $line_member = LineMember::with([
                "line_account",
            ])
            ->where([
                "line_account_id" => $validated["line_account_id"],
                "api_token" => $validated["api_token"],
            ])
            ->whereHas("line_account")
            ->get()
            ->first();

            if ($line_member === null) {
                throw new \Exception("指定したLINEユーザーが見つかりません");
            }

            // --------------------------------------------------------------------
            // Laravel HTTPクライアントを使ってpushメッセージを送信する
            // --------------------------------------------------------------------
            $response = Http::withHeaders([
                "Content-Type" => "application/json",
                "Authorization" => "Bearer {$line_member->line_account->messaging_channel_access_token}",
            ])->post(Config("const.line_login.push"), [
                "to" => $line_member->sub,
                "messages" => $validated["messages"],
            ]);
            $response->throw();

            // httpリクエストが成功したかどうかを検証
            if ($response->successful() !== true) {
                throw new \Exception("LINE側からユーザー情報の取得に失敗しました");
            }
            // pushメッセージのレスポンスは空のjsonオブジェクトを返却する
            $response = $response->json();
        } catch (\Exception $e) {
            return view("errors.index", [
                "e" => $e,
            ]);
        }
    }
}

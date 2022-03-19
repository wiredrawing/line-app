<?php

namespace App\Http\Controllers\Api\Line;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Line\MessageRequest;
use App\Models\LineMember;
use App\Models\LineMessage;
use App\Models\LineReserve;
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

            logger()->info($validated);

            // ----------------------------------------------
            // LINE配信予約レコードを作成する
            // ----------------------------------------------
            $line_reserve = LineReserve::create([
                "line_account_id" => $validated["line_account_id"],
                "delivery_datetime" => $validated["delivery_datetime"]
            ]);
            if ($line_reserve === null) {
                throw new \Exception("LINEの予約配信受付に失敗しました");
            }
            // last sequenceを取得
            $last_line_reserve_id = $line_reserve->id;

            $insert_messages = [];

            foreach ($validated["messages"] as $key => $value) {
                $insert_messages[] = [
                    "line_reserve_id" => $last_line_reserve_id,
                    "type" => $value["type"],
                    "text" => $value["text"],
                ];
            }

            $line_messages = [];
            foreach ($insert_messages as $key => $value) {
                $result = LineMessage::create($value);
                if ($result === null) {
                    throw new \Exception("LINEメッセージの予約投稿に失敗しました");
                }
                $line_messages[] = $result;
            }


            if (count($line_messages) === 0) {
                throw new \Exception("メッセージの予約に失敗しました");
            }

            return response()->json([
                "status" => true,
                "response" => $line_messages,
            ]);
        } catch (\Exception $e) {
            logger()->error($e);
            return view("errors.index", [
                "e" => $e,
            ]);
        }
    }

    /**
     * 現時点で未配信のメッセージ一覧を取得する
     *
     * @param MessageRequest $request
     * @param integer $line_account_id
     * @return void
     */
    public function unsentMessages(MessageRequest $request, int $line_account_id)
    {
        try {
            $validated = $request->validated();
            $line_reserves = LineReserve::with([
                "line_messages",
            ])
            ->whereHas("line_messages")
            ->where("line_account_id", $validated["line_account_id"])
            ->where("is_sent", Config("const.binary_type.off"))
            ->where("delivery_datetime", ">=", date("Y-m-d H:i:s"))
            ->get();

            $response = [
                "status" => true,
                "response" => $line_reserves,
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            logger()->error($e);
            return view("errors.index", [
                "e" => $e,
            ]);
        }
    }

    /**
     * 現時点で配信済みのメッセージ一覧を取得する
     *
     * @param MessageRequest $request
     * @param integer $line_account_id
     * @return void
     */
    public function sentMessages(MessageRequest $request, int $line_account_id)
    {
        try {
            $validated = $request->validated();
            $line_reserves = LineReserve::with([
                "line_messages",
            ])
            ->whereHas("line_messages")
            ->where("line_account_id", $validated["line_account_id"])
            // 配信予定日時が過去のレコード
            ->where("delivery_datetime", "<=", date("Y-m-d H:i:s"))
            // 配信完了フラグがOnのもののみ
            ->where("is_sent", Config("const.binary_type.on"))
            ->get();

            $response = [
                "status" => true,
                "response" => $line_reserves,
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            logger()->error($e);
            return view("errors.index", [
                "e" => $e,
            ]);
        }
    }

    /**
     * 指定したline_reserve_idのレコードを指定したLINEユーザーへ配信する
     *
     * @param MessageRequest $request
     * @param integer $line_account_id
     * @return void
     */
    public function push(MessageRequest $request, int $line_reserve_id)
    {
        try {
            $validated = $request->validated();

            $line_reserve = LineReserve::with([
                "line_messages",
            ])
            ->where("id", $validated["line_reserve_id"])
            ->where("is_displayed", Config("const.binary_type.on"))
            ->where("is_sent", Config("const.binary_type.off"))
            ->whereHas("line_messages")
            ->get()
            ->first();

            if ($line_reserve === null) {
                throw new \Exception("指定したLINE予約メッセージが見つかりません");
            }
            $messages_to_push = [];
            foreach ($line_reserve->line_messages as $index => $message) {
                $messages_to_push[] = $message->toArray();
            }

            // 指定したLINE予約から対象のLINEチャンネルと紐づくLINEメンバーを取得
            $line_members = LineMember::with([
                "line_account",
            ])->where([
                "line_account_id" => $line_reserve->line_account_id,
            ])
            ->get();

            if ($line_members->count() === 0) {
                throw new \Exception("指定したLINE予約を受け取れるLINEメンバーがいません");
            }

            foreach ($line_members as $index => $member) {
                // --------------------------------------------------------------------
                // Laravel HTTPクライアントを使ってpushメッセージを送信する
                // ここで使用するAPIエンドポイントはLINEアカウント側のアクセストークンを利用する
                // ※LINEユーザーのアクセストークンは使わない
                // --------------------------------------------------------------------
                $messages_to_member = [
                    "to" => $member->sub,
                    "messages" => $messages_to_push,
                ];
                $response = Http::withHeaders([
                    "Content-Type" => "application/json",
                    "Authorization" => "Bearer {$member->line_account->messaging_channel_access_token}",
                ])->post(Config("const.line_login.push"), $messages_to_member);

                // 送信先メンバーをログに残す
                logger()->info($messages_to_member);

                $response->throw();

                // httpリクエストが成功したかどうかを検証
                if ($response->successful() !== true) {
                    logger()->error("LINEメッセージの送信に失敗しました");
                    throw new \Exception();
                }
                // pushメッセージのレスポンスは空のjsonオブジェクトを返却する
                $response = $response->json();
                logger()->info($response);
            }

            // --------------------------------------------------
            // 送信した $line_reserveの送信完了フラグをOnにする
            // --------------------------------------------------
            $result = LineReserve::where([
                "id" => $validated["line_reserve_id"],
            ])
            ->update([
                "is_sent" => Config("const.binary_type.on")
            ]);
            if ($result !== true) {
                throw new \Exception("LINE予約を送信済みに変更することができませんでした");
            }
            // $line_member = LineMember::with([
            //     "line_account",
            // ])
            // ->where([
            //     "line_account_id" => $validated["line_account_id"],
            //     "api_token" => $validated["api_token"],
            // ])
            // ->whereHas("line_account")
            // ->get()
            // ->first();

            // if ($line_member === null) {
            //     throw new \Exception("指定したLINEユーザーが見つかりません");
            // }

            // // --------------------------------------------------------------------
            // // Laravel HTTPクライアントを使ってpushメッセージを送信する
            // // --------------------------------------------------------------------
            // $response = Http::withHeaders([
            //     "Content-Type" => "application/json",
            //     "Authorization" => "Bearer {$line_member->line_account->messaging_channel_access_token}",
            // ])->post(Config("const.line_login.push"), [
            //     "to" => $line_member->sub,
            //     "messages" => $validated["messages"],
            // ]);
            // $response->throw();

            // // httpリクエストが成功したかどうかを検証
            // if ($response->successful() !== true) {
            //     throw new \Exception("LINE側からユーザー情報の取得に失敗しました");
            // }
            // // pushメッセージのレスポンスは空のjsonオブジェクトを返却する
            // $response = $response->json();
        } catch (\Exception $e) {
            $json = [
                "status" => false,
                "response" => null,
                "error" => $e->getMessage(),
            ];
            logger()->error($e);
            return response()->json($json);
        }
    }
}

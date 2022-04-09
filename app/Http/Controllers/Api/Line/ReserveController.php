<?php

namespace App\Http\Controllers\Api\Line;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Line\ReserveRequest;
use App\Models\LineBroadcast;
use App\Models\LineMember;
use App\Models\LineMessage;
use App\Models\LineReserve;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ReserveController extends Controller
{


    // POSTデータのフォーマット
    // {
    //     "api_token" : "something key",
    //     "delivery_datetime": "2022-03-10 07:28",
    //     "messages": [
    //         {
    //         "type": "text",
    //         "text": "2"
    //         },
    //         .....
    //     ]
    // }
    /**
     * 指定したLINEアカウントでメッセージを予約する
     *
     * @param ReserveRequest $request
     * @param integer $line_account_id
     * @return void
     */
    public function reserve(ReserveRequest $request, int $line_account_id)
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
     * @param ReserveRequest $request
     * @param integer $line_account_id
     * @return void
     */
    public function unsentMessages(ReserveRequest $request, int $line_account_id)
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
     * @param ReserveRequest $request
     * @param integer $line_account_id
     * @return void
     */
    public function sentMessages(ReserveRequest $request, int $line_account_id)
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
     * @param ReserveRequest $request
     * @param integer $line_account_id
     * @return void
     */
    public function push(ReserveRequest $request, int $line_reserve_id)
    {
        try {
            // トランザクションを開始
            DB::beginTransaction();

            $validated = $request->validated();
            logger()->info($validated);

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

            // メッセージの送信が確定したmembers.id
            $completed_members = [];
            // メッセージの送信確定日
            $delivered_at = Carbon::now("Asia/Tokyo")->format("Y-m-d H:i:s");

            foreach ($line_members as $index => $member) {
                $completed_members[] = [
                    "line_reserve_id" => $line_reserve_id,
                    "line_member_id" => $member->id,
                    "delivered_at" => $delivered_at,
                    "created_at" => $delivered_at,
                    "updated_at" => $delivered_at,
                ];
            }

            // --------------------------------------------------
            // 送信した $line_reserveの送信完了フラグをOnにする
            // --------------------------------------------------
            $result = LineReserve::find($line_reserve_id)
            ->update([
                "is_sent" => Config("const.binary_type.on")
            ]);
            if ($result !== true) {
                throw new \Exception("LINE予約を送信済みに変更することができませんでした");
            }

            // --------------------------------------------------
            // メッセージの配信履歴をline_broadcastsレコードに残す
            // --------------------------------------------------
            $result = LineBroadcast::insert($completed_members);

            if ($result !== true) {
                throw new \Exception("Push送信に失敗しました");
            }

            DB::commit();

            // DBへの問い合わせが管理用したら実際の送信処理を実行
            foreach ($line_members as $index => $member) {
                try {
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
                } catch (\Exception $e) {
                    logger()->error($e);
                }
            }
            $line_reserve_log = LineReserve::with([
                "line_messages",
                "line_broadcasts",
            ])->find($line_reserve_id);

            $json = [
                "status" => true,
                "response" => $line_reserve_log,
                "error" => null,
            ];
            logger()->info($json);
            return response()->json($json);
        } catch (\Exception $e) {
            DB::rollback();
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

<?php

namespace App\Http\Controllers\Admin\Line;

use App\Http\Controllers\Controller;
use App\Models\LineReserve;
use App\Models\LineMessage;
use Illuminate\Http\Request;

class MessageController extends Controller
{



    /**
     * 登録済みの全メッセージを取得
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        try {
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
    }


    /**
     * 未送信の予約済みメッセージ一覧
     *
     * @param Request $request
     * @return void
     */
    public function reserved(Request $request)
    {
        try {
            $reserves = LineReserve::with([
                "line_messages"
            ])
            ->whereHas("line_messages")
            ->orderBy("delivery_datetime", "desc")
            ->orderBy("is_sent", Config("const.binary_type.off"))
            ->get();

            print_r($reserves->toArray());
            return view("admin.line.message.reserved", [
                "reserves" => $reserves,
            ]);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
    }


    /**
     * 送信済みメッセージ一覧
     *
     * @param Request $request
     * @return void
     */
    public function sent(Request $request)
    {
        try {
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
    }
}

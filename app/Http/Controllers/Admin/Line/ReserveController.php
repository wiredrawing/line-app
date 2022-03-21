<?php

namespace App\Http\Controllers\Admin\Line;

use App\Http\Controllers\Controller;
use App\Models\LineReserve;
use App\Models\LineMessage;
use Illuminate\Http\Request;

class ReserveController extends Controller
{



    /**
     * 予約済みの全メッセージを取得
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        try {
            $reserves = LineReserve::orderBy("delivery_datetime", "desc")->get();
            print_r($reserves->toArray());

            return view("admin.line.reserve.index", [
                "reserves" => $reserves,
            ]);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
    }

    /**
     * 指定したLineReserveIdの詳細情報を取得する
     *
     * @param Request $request
     * @param integer $line_reserve_id
     * @return void
     */
    public function detail(Request $request, int $line_reserve_id)
    {
        try {
            // 任意のline_reserve_id
            $reserve = LineReserve::with([
                "line_messages"
            ])
            ->whereHas("line_messages")
            ->findOrFail($line_reserve_id);

            return view("admin.line.reserve.detail", [
                "reserve" => $reserve,
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
            // 任意のline_reserve_id
            $reserve = LineReserve::with([
                "line_messages"
            ])
            ->where([
                "is_sent" => Config("const.binary_type.on"),
            ])
            ->whereHas("line_messages")
            ->get();

            return view("admin.line.reserve.sent", [
                "reserve" => $reserve,
            ]);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
    }
}

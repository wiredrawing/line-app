<?php

namespace App\Http\Controllers\Admin\Line;

use App\Http\Controllers\Controller;
use App\Models\LineReserve;
use App\Models\LineMessage;
use App\Http\Requests\Admin\Base\Line\ReserveRequest;
use Illuminate\Http\Request;

class ReserveController extends Controller
{



    /**
     * 予約済みの全メッセージを取得
     * (※配信済み,未配信問わず出力)
     *
     * @param ReserveRequest $request
     * @return void
     */
    public function index(ReserveRequest $request)
    {
        try {
            $reserves = LineReserve::with([
                "line_messages",
            ])
            ->whereHas("line_messages")
            ->orderBy("delivery_datetime", "desc")
            ->orderBy("created_at", "desc")
            ->get();

            return view("admin.line.reserve.index", [
                "reserves" => $reserves,
            ]);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            logger()->error($e);
        }
    }

    /**
     * 指定したLineReserveIdの詳細情報を取得する
     *
     * @param ReserveRequest $request
     * @param integer $line_reserve_id
     * @return void
     */
    public function detail(ReserveRequest $request, int $line_reserve_id)
    {
        try {
            $validated = $request->validated();
            logger()->info($validated);

            // 任意のline_reserve_id
            $reserve = LineReserve::with([
                "line_messages"
            ])
            ->whereHas("line_messages")
            ->find($line_reserve_id);

            if ($reserve === null) {
                throw new \Exception("指定した予約メッセージが見つかりませんでした");
            }

            return view("admin.line.reserve.detail", [
                "reserve" => $reserve,
            ]);
        } catch (\Exception $e) {
            logger()->error($e);
            var_dump($e->getMessage());
        }
    }




    /**
     * 送信済みメッセージ一覧
     *
     * @param ReserveRequest $request
     * @return void
     */
    public function sent(ReserveRequest $request)
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



    /**
     * 新規LINEメッセージを予約する
     *
     * @param ReserveRequest $request
     * @return void
     */
    public function register(ReserveRequest $request)
    {
        try {

            $validated = $request->validated();
            logger()->info($validated);



        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
    }
}

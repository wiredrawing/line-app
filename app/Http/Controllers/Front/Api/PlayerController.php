<?php

namespace App\Http\Controllers\Front\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\Api\PlayerRequest;
use Illuminate\Http\Request;

class PlayerController extends Controller
{


    /**
     * 指定したplayer_idのプレイヤー情報を取得する
     *
     * @param PlayerRequest $request
     * @param int $player_id
     * @return void
     */
    public function detail(PlayerRequest $request, int $player_id)
    {

    }


    /**
     * 検索可能なゲームプレイヤー一覧を返却
     *
     * @param PlayerRequest $request
     * @return void
     */
    public function list(PlayerRequest $request)
    {

    }


    /**
     * プレイヤー情報の検索
     *
     * @param PlayerRequest $request
     * @return void
     */
    public function search(PlayerRequest $request)
    {

    }

    /**
     * 指定したplayer_idのプレイヤー情報を更新する
     *
     * @param PlayerRequest $request
     * @param int $player_id
     * @return void
     */
    public function update(PlayerRequest $request, int $player_id)
    {

    }
}

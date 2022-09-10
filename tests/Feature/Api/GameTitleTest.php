<?php

namespace Tests\Feature\Api;

use App\Models\GameTitle;
use App\Models\LineAccount;
use App\Models\LineMember;
use App\Models\Player;
use Tests\TestCase;

class GameTitleTest extends TestCase
{
    // use RefreshDatabase;
    /**
     * 新規のゲームタイトル登録処理
     *
     * @return void
     */
    public function test_create_title()
    {
        $line_account = LineAccount::factory()
            ->count(1)
            ->has(LineMember::factory()
                ->count(10)
                ->has(Player::factory(), "player"), "line_members",)
            ->create();

        $line_account = LineAccount::with([
            "line_members",
            "line_members.player",
        ])
            ->get()
            ->first();

        $player_id = $line_account->line_members->first()->player->id;

        $game_titles = GameTitle::factory([
            "created_by" => $player_id,
            "updated_by" => $player_id,
        ])
            ->count(10)
            ->create();

        // print_r($game_titles->toArray());

        $player = Player::get()->last();

        $post_data = [
            "title" => "ゲームタイトル game title".__FUNCTION__,
            // プラットフォームはPS4
            "platform_id" => Config("const.platform_code.PS4"),
            "description" => "ゲームタイトル概要",
            "genre_id" => Config("const.genre_code.FPS"),
            "api_token" => $player->api_token,
            "created_by" => $player->id,
            "updated_by" => $player->id,
        ];
        $response = $this->post(route("front.api.top.gameTitle.create"), $post_data);

        $last_game_title = GameTitle::get()->last();
        // $game_title = GameTitle::create($post_data);

        $expected_json = [
            "status" => true,
            "code" => 201,
            "response" => [
                "game_title" => $last_game_title->toArray(),
            ]
        ];
        // print_r($expected_json);
        // 新規リソース作成用APIの場合は201が返却される
        $response->assertStatus(201);
        $response->assertSee(json_encode($expected_json), false);
        $response->assertSeeText(json_encode($expected_json), false);
        $response->assertExactJson($expected_json);
        $response->assertJson($expected_json);
    }


    /**
     * 作成済みのゲームタイトルをアップデートする処理
     *
     * @return void
     */
    public function test_update_title()
    {
        // ダミーユーザーデータを作成
        $line_account = LineAccount::factory()->count(1)->has(
            LineMember::factory()->count(10)->has(
                Player::factory()->count(10),
                "player"
            ),
            "line_members",
        )->create();
        logger()->info(print_r($line_account->toArray(), true));

        $player = Player::get()->last();
        logger()->info(print_r($player, true));

        $api_token = $player->api_token;
        $post_data = [
            "title" => "ゲームタイトル game title".__FUNCTION__.date("Y-m-d H:i:s"),
            // プラットフォームはPS4
            "platform_id" => Config("const.platform_code.PS4"),
            "description" => "ゲームタイトル概要".__FUNCTION__.date("Y-m-d H:i:s"),
            "genre_id" => Config("const.genre_code.FPS"),
            "api_token" => $api_token,
        ];

        $response = $this->post(route("front.api.top.gameTitle.create"), $post_data);
        // postリクエスト後,最新のgame_titleレコードを取得して結果と照合する
        $game_title = GameTitle::get()->last();
        $expected_data = [
            "status" => true,
            "code" => 201,
            "response" => [
                "game_title" => $game_title->toArray(),
            ]
        ];

        $response->assertStatus(201);
        $response->assertExactJson($expected_data);
        $response->assertJson($expected_data);
        $response->assertSee(json_encode($expected_data), false);
        $response->assertSeeText(json_encode($expected_data), false);
        $latest_game_titile = $response->json();

        // 指定したゲームタイトルを更新する
        $update_post_data = [
            "title" => "ゲームタイトル game title update".__FUNCTION__.date("Y-m-d H:i:s"),
            // プラットフォームはPS4
            "platform_id" => Config("const.platform_code.PS4"),
            "description" => "ゲームタイトル概要 update".__FUNCTION__.date("Y-m-d H:i:s"),
            "genre_id" => Config("const.genre_code.FPS"),
            "api_token" => $api_token,
        ];
        $response = $this->post(route("front.api.top.gameTitle.update", [
            "game_title_id" => $latest_game_titile["response"]["game_title"]["id"],
        ]), $update_post_data);

        $latest_game_titile = GameTitle::findOrFail($latest_game_titile["response"]["game_title"]["id"]);

        $expected_data = [
            "status" => true,
            "code" => 200,
            "response" => [
                "game_title" => $latest_game_titile->toArray(),
            ]
        ];
        $response->assertStatus(200);
        $response->assertExactJson($expected_data);
        $response->assertJson($expected_data);
        $response->assertSee(json_encode($expected_data), false);
        $response->assertSeeText(json_encode($expected_data), false);
    }
}

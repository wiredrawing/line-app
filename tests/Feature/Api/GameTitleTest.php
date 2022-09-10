<?php

namespace Api;

use App\Models\GameTitle;
use App\Models\LineAccount;
use App\Models\LineMember;
use App\Models\Player;
use Illuminate\Cache\RetrievesMultipleKeys;
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

        // print_r($line_account->toArray());

        $game_titles = GameTitle::factory([
            "created_by" => 1,
            "updated_by" => 1,
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
        print_r($expected_json);
        // 新規リソース作成用APIの場合は201が返却される
        $response->assertStatus(201);
        $response->assertSee(json_encode($expected_json), false);
        $response->assertSeeText(json_encode($expected_json), false);
        $response->assertExactJson($expected_json);
        $response->assertJson($expected_json);
    }


    public function test_update_title()
    {
        // try {
        //     // 先に新規リソースを作成する
        //     $player = Player::factory()->create();
        //     if (isset($player->api_token) !== true) {
        //         throw new \Exception("Failed creating player data to execute feature test.");
        //     }
        //
        //     $api_token = $player->api_token;
        //     $post_data = [
        //         "title" => "ゲームタイトル game title".__FUNCTION__,
        //         // プラットフォームはPS4
        //         "platform_id" => Config("const.platform_code.PS4"),
        //         "description" => "ゲームタイトル概要",
        //         "genre_id" => Config("const.genre_code.FPS"),
        //         "api_token" => $api_token,
        //     ];
        //     $response = $this->post("/front/api/title/create", $post_data);
        //     $latest_game_title = $response->json();
        //     $latest_game_title_id = $latest_game_title["response"]["game_title"]["id"];
        //
        //     // 新規で作成したゲームタイトルをアップデートする
        //     $update_post_data = [
        //         "title" => "ゲームタイトル game title update".__FUNCTION__,
        //         // プラットフォームはPS4
        //         "platform_id" => Config("const.platform_code.PS4"),
        //         "description" => "ゲームタイトル概要 update",
        //         "genre_id" => Config("const.genre_code.FPS"),
        //         "api_token" => $api_token,
        //     ];
        //     $response = $this->post("/front/api/title/update/{$latest_game_title_id}", $update_post_data);
        //     $response->assertStatus(200);
        //     $response->assertSee(__FUNCTION__);
        // } catch (\Throwable $e) {
        //     var_dump($e->getMessage());
        //     return -1;
        // }
    }
}

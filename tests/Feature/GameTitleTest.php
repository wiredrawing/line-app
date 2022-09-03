<?php

namespace Tests\Feature;

use App\Models\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GameTitleTest extends TestCase
{
    // use RefreshDatabase;
    /**
     * 新規のゲームタイトル登録処理
     * @return void
     */
    public function test_create_title()
    {
        $player = Player::factory()->create();
        $api_token = $player->api_token;
        $post_data = [
            "title" => "ゲームタイトル game title".__FUNCTION__,
            // プラットフォームはPS4
            "platform_id" => Config("const.platform_code.PS4"),
            "description" => "ゲームタイトル概要",
            "genre_id" => Config("const.genre_code.FPS"),
            "api_token" => $api_token,
        ];
        $response = $this->post("/front/api/title/create", $post_data);

        // 新規リソース作成用APIの場合は201が返却される
        $response->assertStatus(201);
        $response->assertSee("game title");
        $response->assertSee(__FUNCTION__);
    }



    public function test_update_title()
    {
        try {
            // 先に新規リソースを作成する
            $player = Player::factory()->create();
            if (isset($player->api_token) !== true) {
                throw new \Exception("Failed creating player data to execute feature test.");
            }

            $api_token = $player->api_token;
            $post_data = [
                "title" => "ゲームタイトル game title".__FUNCTION__,
                // プラットフォームはPS4
                "platform_id" => Config("const.platform_code.PS4"),
                "description" => "ゲームタイトル概要",
                "genre_id" => Config("const.genre_code.FPS"),
                "api_token" => $api_token,
            ];
            $response = $this->post("/front/api/title/create", $post_data);
            $latest_game_title = $response->json();
            $latest_game_title_id = $latest_game_title["response"]["game_title"]["id"];

            // 新規で作成したゲームタイトルをアップデートする
            $update_post_data = [
                "title" => "ゲームタイトル game title update".__FUNCTION__,
                // プラットフォームはPS4
                "platform_id" => Config("const.platform_code.PS4"),
                "description" => "ゲームタイトル概要 update",
                "genre_id" => Config("const.genre_code.FPS"),
                "api_token" => $api_token,
            ];
            $response = $this->post("/front/api/title/update/{$latest_game_title_id}", $update_post_data);
            $response->assertStatus(200);
            $response->assertSee(__FUNCTION__);
        } catch (\Throwable $e) {
            var_dump($e->getMessage());
            return -1;
        }
    }
}

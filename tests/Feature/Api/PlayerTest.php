<?php

namespace Tests\Feature\Api;

use App\Models\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use const http\Client\Curl\POSTREDIR_ALL;

class PlayerTest extends TestCase
{


    /**
     * 指定したplayerの情報のアップデート処理
     * @return void
     */
    /**
     * @return int|void
     */
    public function test_update_player()
    {
        try {
            // 新規player情報の登録
            $player = Player::factory()->create();

            if ($player === null) {
                throw new \Exception("Failed creating fake player info.");
            }

            $post_data = [
                "id" => $player->id,
                "family_name" => "プレイヤー情報アップデート family_name",
                "middle_name" => "プレイヤー情報アップデート middle_name",
                "given_name" => "プレイヤー情報アップデート given_name",
                "nickname" => "プレイヤー情報アップデート nickname",
                "gender_id" => 1,
                "is_published" => 1,
                "description" => "プレイヤー情報をアップデートします description",
                "memo" => "プレイヤー情報をアップデート memo",
                "api_token" => $player->api_token,
            ];
            $response = $this->post("/front/api/player/update/{$player->id}", $post_data);
            $response->assertStatus(200);
            return 1;
        } catch (\Throwable $e) {
            var_dump($e->getMessage());
            return -1;
        }
    }
}

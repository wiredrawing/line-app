<?php

namespace Tests\Feature\Api;

use App\Models\Follower;
use App\Models\LineAccount;
use App\Models\LineMember;
use App\Models\Player;
use Tests\TestCase;

class FollowerTest extends TestCase
{

    /**
     * 自分以外のプレイヤーをフォローする
     *
     * @return void
     */
    public function test_follow_other_player()
    {
        // ----------------------------------------------
        // ダミーデータを作成する
        // ----------------------------------------------
        $line_account = LineAccount::factory()
            ->count(1)
            ->has(LineMember::factory()
                ->count(10)
                ->has(Player::factory()
                    ->count(1), "player"), "line_members")
            ->create();

        // 作成されたダミープレイヤー情報を取得
        $from_player = Player::get()->first();
        $to_player = Player::get()->last();

        $response = $this->post(route("front.api.top.follower.create"), [
            "from_player_id" => $from_player->id,
            "to_player_id" => $to_player->id,
            "api_token" => $from_player->api_token,
        ]);

        $follower = Follower::where([
            "from_player_id" => $from_player->id,
            "to_player_id" => $to_player->id,
        ])
            ->get()
            ->first();


        $expected_json = [
            "status" => true,
            "code" => 201,
            "response" => [
                "follower" => $follower->toArray(),
            ],
        ];
        $response->assertStatus(201);
        $response->assertExactJson($expected_json);
        $response->assertJson($expected_json);
        $response->assertSee(json_encode($expected_json), false);
        $response->assertSeeText(json_encode($expected_json), false);
    }


    /**
     * 指定した他のプレイヤーのフォローを外す
     *
     * @return void
     */
    public function test_unfollow_other_player()
    {
        // ----------------------------------------------
        // ダミーデータを作成する
        // ----------------------------------------------
        $line_account = LineAccount::factory()
            ->count(1)
            ->has(LineMember::factory()
                ->count(10)
                ->has(Player::factory()
                    ->count(1), "player"), "line_members")
            ->create();

        // 作成されたダミープレイヤー情報を取得
        $from_player = Player::get()->first();
        $to_player = Player::get()->last();
        $follower = Follower::factory([
            "from_player_id" => $from_player->id,
            "to_player_id" => $to_player->id,
        ])->create();


        $response = $this->post(route("front.api.top.follower.delete"), [
            "from_player_id" => $from_player->id,
            "to_player_id" => $to_player->id,
            "api_token" => $from_player->api_token,
        ]);

        $expected_json = [
            "status" => true,
            "code" => 200,
            "response" => [
                // 削除されたフォロワーのIDが返却される
                "follower" => [
                    "id" => $follower->id,
                ],
            ],
        ];
        $response->assertStatus(200);
        $response->assertExactJson($expected_json);
        $response->assertJson($expected_json);
        $response->assertSee(json_encode($expected_json), false);
        $response->assertSeeText(json_encode($expected_json), false);
    }
}

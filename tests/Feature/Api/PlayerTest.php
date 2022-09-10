<?php

namespace Tests\Feature\Api;

use App\Models\LineAccount;
use App\Models\LineMember;
use App\Models\Player;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlayerTest extends TestCase
{

    use RefreshDatabase;


    /**
     * 指定したプレイヤーの情報を取得する
     * @return void
     */
    public function test_fetch_player_detail()
    {
        $line_account = LineAccount::factory()
            ->count(1)
            ->has(LineMember::factory()
                ->count(1)
                ->has(Player::factory()
                    ->count(1), "player",), "line_members",)
            ->create();

        $line_account = LineAccount::with([
            "line_members",
            "line_members.player",
        ])
            ->get()
            ->first();

        // print_r($line_account->toArray());


        $player = Player::with([
            "line_member",
            "line_member.line_account",
        ])
            ->get()
            ->first();

        $response = $this->get(route("front.api.top.player.detail", [
            "player_id" => $line_account->first()->line_members->first()->player->id,
            "api_token" => $line_account->first()->line_members->first()->player->api_token,
        ]));

        $expected_json = [
            "status" => true,
            "code" => 200,
            "response" => [
                "player" => $player->toArray(),
            ],
        ];
        $response->assertSee(200);
        $response->assertExactJson($expected_json);
        $response->assertJson($expected_json);
        $response->assertSee(json_encode($expected_json), false);
        $response->assertSeeText(json_encode($expected_json), false);
    }

    /**
     * 指定したplayerの情報のアップデート処理
     *
     * @return void
     */
    public function test_update_player()
    {
        // 新規player情報の登録
        $line_account = LineAccount::factory()
            ->count(1)
            ->has(LineMember::factory()
                ->count(1)
                ->has(Player::factory()
                    ->count(1), "player"), "line_members")
            ->create();

        // logger()->info(print_r($line_account->toArray(), true));

        $line_account = LineAccount::with([
            "line_members",
            "line_members.player",
        ])
            ->get()
            ->first();

        // logger()->info(print_r($line_account->toArray(), true));

        $player = Player::get()->first();

        $response = $this->post(route("front.api.top.player.update", [
            "player_id" => $player->id,
        ]), [
            "id" => $player->id,
            "family_name" => Factory::create()->text(512),
            "middle_name" => Factory::create()->text(512),
            "given_name" => Factory::create()->text(512),
            "nickname" => Factory::create()->text(512),
            "gender_id" => 100,
            "is_published" => 100,
            "description" => Factory::create()->text(512),
            "memo" => Factory::create()->text(512),
            "api_token" => $player->api_token,
        ]);

        $player = Player::with([
            "line_member",
            "line_member.line_account",
        ])
            ->get()
            ->first();
        // print_r($player->toArray());

        $expected_json = [
            "status" => true,
            "code" => 200,
            "response" => [
                "player" => $player->toArray(),
            ]
        ];
        // print_r($expected_json);
        $response->assertStatus(200);
        $response->assertJson($expected_json);
        $response->assertExactJson($expected_json);
        $response->assertSeeText(json_encode($expected_json), false);
        $response->assertSee(json_encode($expected_json), false);
    }
}

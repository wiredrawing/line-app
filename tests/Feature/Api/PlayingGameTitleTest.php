<?php

namespace Tests\Feature\Api;

use App\Models\GameTitle;
use App\Models\LineAccount;
use App\Models\LineMember;
use App\Models\Player;
use App\Models\PlayingGameTitle;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PlayingGameTitleTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_playing_game_title()
    {
        $line_account = LineAccount::factory()
            ->count(1)
            ->has(LineMember::factory()
                ->count(10)
                ->has(Player::factory()
                    ->count(10), "player"), "line_members")
            ->create();

        $line_account = LineAccount::with([
            "line_members",
            "line_members.player",
        ])
            ->get()
            ->first();

        $game_titles = GameTitle::factory([
            "created_by" => $line_account->line_members->first()->player->id,
            "updated_by" => $line_account->line_members->first()->player->id,
        ])
            ->count(10)
            ->create();

        // print_r($game_titles->toArray());

        $response = $this->post(route("front.api.top.playingGameTitle.create"), [
            "player_id" => $line_account->line_members->first()->player->id,
            "api_token" => $line_account->line_members->first()->player->api_token,
            "game_title_id" => $game_titles->first()->id,
            // やりこみ度
            "skill_level" => 1,
            // プレイの頻度(週何回?)
            "frequency" => 3,
            "memo" => Factory::create()->realText(100)
        ]);

        $response->assertStatus(201);
    }


    /**
     * 指定したプレイヤーが現在プレイ中のゲームタイトル一覧を取得する
     * @return void
     */
    public function test_fetch_game_titles_per_player()
    {
        $line_account = LineAccount::factory()
            ->count(1)
            ->has(LineMember::factory()
                ->count(10)
                ->has(Player::factory()
                    ->count(10), "player",), "line_members",)
            ->create();

        $player = Player::get()
            ->last();
        $game_titles = GameTitle::factory([
            "created_by" => $player->id,
            "updated_by" => $player->id,
        ])
            ->count(20)
            ->has(PlayingGameTitle::factory()
                ->state(function (array $attributes, GameTitle $game_title) {
                    $player = Player::get()
                        ->last();
                    return [
                        "player_id" => $player->id,
                    ];
                }), "playing_game_titles")
            ->create();

        $game_titles = GameTitle::with([
            "playing_game_titles",
        ])
            ->get();

        $response = $this->get(route("front.api.top.playingGameTitle.detail", [
            "player_id" => $player->id,
        ]));

        $expected_data = [
            "status" => true,
            "code" => 200,
            "response" => [
                "playing_game_titles" => null,
            ]
        ];
        $response->assertExactJson($expected_data);
    }
}

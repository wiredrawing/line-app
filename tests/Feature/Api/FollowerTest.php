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
     * @test
     * @return void
     */
    public function test_自分以外のプレイヤーをフォローする()
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
        $from_player = Player::all()->get(0);
        $to_player = Player::all()->get(1);
        // ダミーのプレイヤー情報をもとにダミーのフォロー関係を作成する
        $result = Follower::factory([
            "from_player_id" => $from_player->id,
            "to_player_id" => $to_player->id,
        ])->count(1)->create();

        // 実際に挿入したテストのフォロー関係
        $follower = Follower::where([
            "from_player_id" => $from_player->id,
            "to_player_id" => $to_player->id,
        ])
            ->get()
            ->first();


        // テストのhttpリクエストを送信
        $to_player = Player::all()->get(3);
        $response = $this->post(route("front.api.top.follower.create"), [
            "from_player_id" => $from_player->id,
            "to_player_id" => $to_player->id,
            "api_token" => $from_player->api_token,
        ]);


        $expected_json = [
            "status" => true,
            "code" => 201,
            "response" => [
                "follower" => $follower->toArray(),
            ],
        ];
        $response->assertStatus(201);
        // POST系リクエストの場合APIのレスポンスのjson構造がマッチすればOKとする
        $response->assertJsonStructure([
            "status",
            "code",
            "response" => [
                "follower" => [
                    "updated_at",
                    "created_at",
                    "matched_at",
                    "to_player_id",
                    "from_player_id",
                    "id",
                ],
            ],
        ], $expected_json);
    }


    /**
     * 指定した他のプレイヤーのフォローを外す
     *
     * @test
     * @return void
     */
    public function test_フォロー中の他プレイヤーのフォローを外す()
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
        // テスト用のフォロー関係レコードを作成
        $follower = Follower::factory([
            "from_player_id" => $from_player->id,
            "to_player_id" => $to_player->id,
        ])
            ->create();

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
        // APIのjson構造体の構成チェック
        $response->assertJsonStructure([
            "status",
            "code",
            "response" => [
                "follower" => [
                    "id",
                ]
            ]
        ]);
    }

    /**
     * 指定したプレイヤーがフォロー中の他プレイヤー一覧を取得する
     *
     * @test
     * @return void
     */
    public function test_現在フォローしている他プレイヤー一覧を取得する()
    {
        // ------------------------------------------------------------
        // ダミーのプレイヤーデータ一覧を作成する
        // ------------------------------------------------------------
        $line_account = LineAccount::factory()
            ->count(1)
            ->has(LineMember::factory()
                ->count(100)
                ->has(Player::factory()
                    ->count(1), "player",), "line_members",)
            ->create();

        // ベースとなるプレイヤー情報を取得する
        $player = Player::orderBy("id", "asc")
            ->get()
            ->first();
        $players = Player::where("id", "!=", $player->id)
            ->get();
        foreach ($players as $key => $value) {
            $p = Follower::where([
                "from_player_id" => $player->id,
                "to_player_id" => $value->id,
            ])
                ->get()
                ->first();
            if ($p !== null) {
                break;
            }
            $p = Follower::create([
                "from_player_id" => $player->id,
                "to_player_id" => $value->id,
            ]);
            logger()->info(print_r($p->toArray(), true));
        }

        $follwers = Follower::where([
            "from_player_id" => $player->id,
        ])
            ->get();

        $expected_json = [
            "status" => true,
            "code" => 200,
            "response" => [
                "followers" => $follwers->toArray(),
            ],
        ];

        logger()->info(print_r($expected_json, true));

        $response = $this->get(route("front.api.top.follower.following", [
            "from_player_id" => $player->id,
            "api_token" => $player->api_token,
        ]));
        $response->assertStatus(200);
        $response->assertExactJson($expected_json);
        $response->assertJson($expected_json);
        $response->assertSee(json_encode($expected_json), false);
        $response->assertSeeText(json_encode($expected_json), false);
    }
}

<?php

namespace Database\Factories;

use App\Libraries\RandomToken;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "line_member_id" => 1,
            "family_name" => Str::random(10),
            "middle_name" => Str::random(10),
            "given_name" => Str::random(10),
            "nickname" => Str::random(10),
            // API経由ではメールアドレスは変更させない
            "email" => Str::random(10) . "@". Str::random(10),
            "description" => Str::random(100),
            "is_displayed" => 1,
            "is_deleted" => 0,
            "gender_id" => 1,
            "is_published" => 1,
            "api_token" => RandomToken::MakeRandomToken(128),
            "memo" => "",
        ];
    }
}

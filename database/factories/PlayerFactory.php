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
            "family_name" => $this->faker->firstName(),
            "middle_name" => Str::random(10),
            "given_name" => $this->faker->lastName(),
            "nickname" => Str::random(10),
            "description" => Str::random(100),
            "is_displayed" => Config("const.binary_type.on"),
            "is_deleted"  => Config("const.binary_type.off"),
            "gender_id" => 1,
            "is_published" => 1,
            "api_token" => RandomToken::MakeRandomToken(128),
            "memo" => Str::random(128),
        ];
    }
}

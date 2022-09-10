<?php

namespace Database\Factories;

use App\Libraries\RandomToken;
use Illuminate\Database\Eloquent\Factories\Factory;

class LineMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "access_token" => $this->faker->uuid(),
            "expires_in" => 1,
            "id_token" => $this->faker->uuid(),
            "refresh_token" => $this->faker->uuid(),
            "token_type"  => $this->faker->uuid(),
            "email" => $this->faker->email(),
            "picture"  => $this->faker->imageUrl(),
            "name"  => $this->faker->lastName().$this->faker->firstName(),
            "is_displayed" => Config("const.binary_type.on"),
            "is_deleted" => Config("const.binary_type.off"),
            "sub" => $this->faker->uuid(),
            "aud" => $this->faker->uuid(),
            "line_account_id" => 1,
            "api_token" => RandomToken::MakeRandomToken(128),
        ];
    }
}

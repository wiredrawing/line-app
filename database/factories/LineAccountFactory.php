<?php

namespace Database\Factories;

use App\Libraries\RandomToken;
use Illuminate\Database\Eloquent\Factories\Factory;

class LineAccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "channel_name" => $this->faker->name(64),
            "channel_id" => $this->faker->uuid(),
            "channel_secret" =>  $this->faker->uuid(),
            "user_id" =>  $this->faker->uuid(),
            "messaging_channel_id" =>  $this->faker->uuid(),
            "messaging_channel_secret" =>  $this->faker->uuid(),
            "messaging_user_id" => $this->faker->uuid(),
            "messaging_channel_access_token" =>  $this->faker->uuid(),
            "webhook_url" => $this->faker->url(),
            "api_token" => RandomToken::MakeRandomToken(128),
            "application_key" => RandomToken::MakeRandomToken(128),
            "is_displayed" => Config("const.binary_type.on"),
            "is_deleted" => Config("const.binary_type.off"),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FollowerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "from_player_id" => 1,
            "to_player_id" => 2,
            "matched_at" => null,
        ];
    }
}

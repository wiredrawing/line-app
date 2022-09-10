<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PlayingGameTitleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "player_id" => 1,
            "game_title_id" => 1,
            // やりこみ度
            "skill_level" => 1000,
            // プレイの頻度(週何回?)
            "frequency" => 7,
            "memo" => $this->faker->realText(100),
        ];
    }
}

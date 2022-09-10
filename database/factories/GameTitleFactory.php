<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GameTitleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "title" => $this->faker->realText(64),
            "platform_id" => 1,
            "description" => $this->faker->realText(64),
            "genre_id" => 1,
            "is_displayed" => Config("const.binary_type.on"),
            "is_deleted" => Config("const.binary_type.off"),
            "created_by" => 1,
            "updated_by" => 1,
        ];
    }
}

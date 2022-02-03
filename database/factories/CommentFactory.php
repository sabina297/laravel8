<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * 
     */
    public function definition()
    {
        return [
            'content' => $this->faker->text,
            'created_at' => $this->faker->dateTimeBetween('-3 month'),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
}

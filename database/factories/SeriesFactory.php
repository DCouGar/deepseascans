<?php

namespace Database\Factories;

use App\Models\Series;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeriesFactory extends Factory
{
    protected $model = Series::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'author' => $this->faker->name(),
            'artist' => $this->faker->name(),
            'genre' => $this->faker->word(),
            'synopsis' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['Ongoing', 'Completed', 'Hiatus']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
} 
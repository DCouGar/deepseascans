<?php

namespace Database\Factories;

use App\Models\Chapter;
use App\Models\Series;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChapterFactory extends Factory
{
    protected $model = Chapter::class;

    public function definition(): array
    {
        return [
            'series_id' => Series::factory(),
            'number' => $this->faker->unique()->numberBetween(1, 100),
            'title' => $this->faker->sentence(3),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
} 
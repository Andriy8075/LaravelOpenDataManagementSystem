<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Data>
 */
class DataFactory extends Factory
{
    public function definition()
    {
        return [
            'title' => $this->faker->sentence, // Випадковий заголовок
            'description' => $this->faker->paragraph, // Випадковий текст
            'created_by' => 1, // Усі дані належать користувачу з id = 1
        ];
    }
}


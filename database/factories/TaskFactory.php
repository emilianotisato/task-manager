<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => fn () => Project::factory(),
            'title' => $this->faker->words(3, true),
            'description' => $this->faker->sentence,
            'due_date' => $this->faker->dateTimeBetween('- 1 days', '20 days'),
            'postponed_times' => 0,
            'severity_points' => 0,
            'completed' => rand(0, 1),
        ];
    }
}

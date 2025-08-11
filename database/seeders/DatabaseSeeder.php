<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create(['email' => 'emilianotisato@gmail.com']);

        $projects = Project::factory()->times(3)->create();

        $projects->each(function ($project) {
            Task::factory()->times(10)->create(
                [
                    'project_id' => $project->id,
                ]
            );
        });
    }
}

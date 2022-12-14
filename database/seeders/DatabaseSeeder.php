<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Don't change the order
        $this->call([
            CategorySeeder::class,
            QuestionnaireSeeder::class,
            QuestionSeeder::class,
            AnswerSeeder::class,
            UserSeeder::class,
        ]);
    }
}

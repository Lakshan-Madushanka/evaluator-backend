<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Database\Seeder;

class AnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Question::all(['id'])
            ->each(function (Question $question) {
                $answers = Answer::factory()
                    ->count($question->no_of_answers)
                    ->make();

                $question->answers()->createMany($answers);
            });
    }
}

<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Database\Eloquent\Collection;
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
        Question::all()
            ->each(function (Question $question) {
                $answers = Answer::factory()
                    ->count($question->no_of_answers)
                    ->create();
                // ->toArray();

                $this->attachAnswersIds($question, $answers);

                // $question->answers()->createMany($answers);
            });
    }

    public function attachAnswersIds(Question $question, Collection $answers)
    {
        $ids = $answers->pluck('id')->all();
        $noOfIds = count($ids);

        $data = [];

        $noOfCorrectAnswers = 0;

        if ($noOfIds === 2) {
            $noOfCorrectAnswers = 1;
        } else {
            $noOfCorrectAnswers = random_int(1, $noOfIds - 1);
        }

        $count = 0;

        foreach ($ids as $id) {
            if ($count < $noOfCorrectAnswers) {
                $data[$id] = ['correct_answer' => true];
            } else {
                $data[$id] = ['correct_answer' => false];
            }

            $count++;
        }

        $question->answers()->attach($data);
    }
}

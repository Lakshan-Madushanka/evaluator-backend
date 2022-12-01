<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Difficulty;
use App\Models\Question;
use App\Models\Questionnaire;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Questionnaire::query()
            ->with('categories:id')
            ->get()
            ->each(function (Questionnaire $questionnaire) {
                $noOfEasyQuestions = $questionnaire->no_of_easy_questions;
                $noOfMediumQuestions = $questionnaire->no_of_medium_questions;
                $noOfHardQuestions = $questionnaire->no_of_hard_questions;

                $easyQuestions = $questionnaire->questions()->createMany($this->makeQuestions(Difficulty::EASY, $noOfEasyQuestions));
                $mediumQuestions = $questionnaire->questions()->createMany($this->makeQuestions(Difficulty::MEDIUM, $noOfMediumQuestions));
                $hardQuestions = $questionnaire->questions()->createMany($this->makeQuestions(Difficulty::HARD, $noOfHardQuestions));

                $this->assignCategories($easyQuestions, $questionnaire->categories);
                $this->assignCategories($mediumQuestions, $questionnaire->categories);
                $this->assignCategories($hardQuestions, $questionnaire->categories);
            });
    }

    public function makeQuestions(Difficulty $type, int $noOfQuestions): array
    {
        if ($noOfQuestions < 1) {
            return [];
        }

        return Question::factory()
            ->count($noOfQuestions)
            ->make(['difficulty' => $type])
            ->toArray();
    }

    public function assignCategories(array $questions, Collection $categories)
    {
        foreach ($questions as $question) {
            $question->categories()->saveMany($categories);
        }
    }
}

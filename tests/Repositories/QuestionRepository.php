<?php

namespace Tests\Repositories;

use App\Models\Question;
use Illuminate\Database\Eloquent\Collection;

class QuestionRepository
{
    public static function getRandomQuestion(): Question
    {
        return Question::inRandomOrder()->first();
    }

    public static function getTotalQuestionsCount(): int
    {
        return Question::count();
    }

    public static function getLastInsertedRecord(): Question
    {
        return Question::query()
            ->orderByDesc('id')
            ->first();
    }

    public static function getRandomQuestions(): Collection
    {
        return Question::query()
            ->inRandomOrder()
            ->limit(1)
            ->get();
    }
}

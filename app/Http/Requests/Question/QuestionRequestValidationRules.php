<?php

namespace App\Http\Requests\Question;

use App\Enums\Difficulty;
use App\Http\Requests\Contracts\RequestValidationRulesContract;
use Illuminate\Validation\Rules\Enum;

class QuestionRequestValidationRules implements RequestValidationRulesContract
{
    /**
     * @return array<string, mixed>
     */
    public static function getRules(): array
    {
        return [
            'difficulty' => ['required', new Enum(Difficulty::class)],
            'text' => ['string', 'required', 'min:3'],
            'no_of_answers' => ['integer', 'required', 'min:2'],
            'categories' => ['array', 'required'],
            'categories.*' => ['string', 'required'],
        ];
    }
}

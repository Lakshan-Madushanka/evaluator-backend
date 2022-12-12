<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Administrative\Questionnaire\Question;

use App\Enums\Difficulty;
use App\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Questionnaire;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class SyncQuestionController extends Controller
{
    public function __invoke(Questionnaire $questionnaire, Request $request): JsonResponse
    {
        $validatedInputs = $request->validate([
            'questions' => ['array'],
            'questions.*' => ['string'],
        ]);

        $validatedQuestions = $validatedInputs['questions'];

        $modelIds = Helpers::getModelIdsFromHashIds($validatedQuestions);

        $attachableQuestionsModelIds = Question::query()
            ->eligible($questionnaire)
            ->pluck('questions.id');

        $availableModelIds = collect($modelIds)->intersect($attachableQuestionsModelIds);

        $this->validateQuestions($questionnaire, $availableModelIds);

        $questionnaire->questions()->sync($availableModelIds);

        return new JsonResponse(status: Response::HTTP_OK);
    }

    public function validateQuestions(Questionnaire $questionnaire, Collection $modelIds): void
    {
        $noOfTotalQuestions = $modelIds->count();

        $noOfEasyQuestions = Question::whereDifficulty(Difficulty::EASY)
            ->whereIn('id', $modelIds)
            ->count();

        $noOfMediumQuestions = Question::whereDifficulty(Difficulty::MEDIUM)
            ->whereIn('id', $modelIds)
            ->count();

        $noOfHardQuestions = Question::whereDifficulty(Difficulty::HARD)
            ->whereIn('id', $modelIds)
            ->count();

        throw_if(
            $noOfTotalQuestions > $questionnaire->no_of_questions,
            ValidationException::withMessages([
                'questions' => "No of total questions should be {$noOfTotalQuestions}",
            ])
        );

        throw_if(
            $noOfEasyQuestions > $questionnaire->no_of_easy_questions,
            ValidationException::withMessages([
                'questions' => "No of easy questions should be {$noOfEasyQuestions}",
            ])
        );

        throw_if(
            $noOfMediumQuestions > $questionnaire->no_of_medium_questions,
            ValidationException::withMessages([
                'questions' => "No of medium questions should be {$noOfMediumQuestions}",
            ])
        );

        throw_if(
            $noOfHardQuestions > $questionnaire->no_of_hard_questions,
            ValidationException::withMessages([
                'questions' => "No of hard questions should be {$noOfHardQuestions}",
            ])
        );
    }
}

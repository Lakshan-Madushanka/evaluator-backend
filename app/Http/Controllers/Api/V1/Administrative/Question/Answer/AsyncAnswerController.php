<?php

namespace App\Http\Controllers\Api\V1\Administrative\Question\Answer;

use App\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AsyncAnswerController extends Controller
{
    public function __invoke(Question $question, Request $request)
    {
        $validatedInputs = $request->validate([
            'ids' => ['array'],
            'ids.*' => ['string'],
        ]);

        throw_if(
            count($validatedInputs['ids']) > $question->no_of_answers,
            ValidationException::withMessages(['ids' => "Exceeds allowed no of answers ({$question->no_of_answers})"])
        );

        $ids = array_unique(Helpers::getModelIdsFromHashIds($validatedInputs['ids']));

        $results = $question->answers()->sync($ids);

        return new JsonResponse(status: Response::HTTP_OK);
    }
}

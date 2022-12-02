<?php

namespace App\Http\Controllers\Api\V1\Administrative\Answer;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserStoreRequest;
use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AnswerQuestionController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  UserStoreRequest  $request
     * @return JsonResponse
     */
    public function __invoke(Question $question): JsonResponse
    {
        $question->delete();

        return new JsonResponse(status:Response::HTTP_NO_CONTENT);
    }
}

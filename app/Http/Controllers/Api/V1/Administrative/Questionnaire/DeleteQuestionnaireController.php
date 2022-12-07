<?php

namespace App\Http\Controllers\Api\V1\Administrative\Questionnaire;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserStoreRequest;
use App\Models\Question;
use App\Models\Questionnaire;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DeleteQuestionnaireController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  Questionnaire  $questionnaire
     *
     * @return JsonResponse
     */
    public function __invoke(Questionnaire $questionnaire): JsonResponse
    {
        $questionnaire->delete();

        return new JsonResponse(status:Response::HTTP_NO_CONTENT);
    }
}

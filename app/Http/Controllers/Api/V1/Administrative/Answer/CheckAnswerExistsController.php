<?php

namespace App\Http\Controllers\Api\V1\Administrative\Answer;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserStoreRequest;
use App\Models\Answer;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Vinkla\Hashids\Facades\Hashids;

class CheckAnswerExistsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  UserStoreRequest  $request
     * @return JsonResponse
     */
    public function __invoke(string $id): JsonResponse
    {
        $decodedModelId = Hashids::decode($id);
        $modelId = $decodedModelId ?: null;

        $question = null;

        if (! is_null($modelId)) {
            $question = Answer::find($modelId);
        }

        return new JsonResponse(
            [
                'exists' => ! is_null($question),
            ],
            status: Response::HTTP_OK);
    }
}

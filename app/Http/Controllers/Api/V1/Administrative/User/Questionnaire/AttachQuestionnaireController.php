<?php

namespace App\Http\Controllers\Api\V1\Administrative\User\Questionnaire;

use App\Http\Controllers\Controller;
use App\Models\Questionnaire;
use App\Models\User;
use App\Notifications\QuestionnaireAttachedToUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Vinkla\Hashids\Facades\Hashids;

class AttachQuestionnaireController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  User  $user
     * @param  string  $questionnaireId
     * @return JsonResponse|void
     */
    public function __invoke(User $user, string $questionnaireId, Request $request)
    {
        $decodedQuestionId = Hashids::decode($questionnaireId)[0];

        $questionnaire = Questionnaire::query()
            ->whereId($decodedQuestionId)
            ->withCount('questions')
            ->completed(true)
            ->first();

        if (is_null($questionnaire)) {
            return new JsonResponse(data: [
                'eligible' => false,
            ]);
        }

        $code = Str::uuid();
        $user->questionnaires()->attach($decodedQuestionId, ['code' => $code]);

        $user->notify(new QuestionnaireAttachedToUser($code, $request->action_url));
    }
}

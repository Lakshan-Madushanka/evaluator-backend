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
        $decodedQuestionnaireId = Hashids::decode($questionnaireId)[0] ?? null;

        if (is_null($decodedQuestionnaireId)) {
            return new JsonResponse(data: [
                'eligible' => false,
            ]);
        }

        $questionnaire = Questionnaire::query()
            ->whereId($decodedQuestionnaireId)
            ->withCount('questions')
            ->completed(true)
            ->first();

        if (is_null($questionnaire)) {
            return new JsonResponse(data: [
                'eligible' => false,
            ]);
        }

        $code = Str::uuid();
        $expiresAt = now()->addMinutes($questionnaire->allocated_time);

        $user->questionnaires()->attach($decodedQuestionnaireId, ['code' => $code, 'expires_at' => $expiresAt]);

        $user->notify(new QuestionnaireAttachedToUser($code, $request->action_url));
    }
}

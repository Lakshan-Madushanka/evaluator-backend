<?php

namespace App\Http\Controllers\Api\V1\Administrative\Answer;

use App\Http\Controllers\Controller;
use App\Http\Resources\AnswerResource;
use App\Models\Answer;

class ShowAnswerController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  Answer  $question
     * @return AnswerResource
     */
    public function __invoke(Answer $question)
    {
        $question->load(['images']);

        return new AnswerResource($question);
    }
}

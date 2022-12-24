<?php

namespace App\Http\Resources;

use App\Models\Evaluation;
use Illuminate\Http\Request;
use TiMacDonald\JsonApi\JsonApiResource;

/**
 * @mixin Evaluation
 */
class EvaluationResource extends JsonApiResource
{
    public function toAttributes(Request $request): array
    {
        return [
            'marks_percentage' => $this->marks_percentage,
            'total_points_earned' => $this->total_points_earned,
            'total_points_allocated' => $this->total_points_allocated,
            'time_taken' => $this->time_taken,
            'no_of_answered_questions' => $this->no_of_answered_questions,
            'no_of_correct_answers' => $this->correct_answers,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}

<?php

namespace App\Http\Resources;

use App\Models\Evaluation;
use Hashids;
use Illuminate\Http\Request;
use TiMacDonald\JsonApi\JsonApiResource;

/**
 * @mixin Evaluation
 */
class EvaluationResource extends JsonApiResource
{
    public function toAttributes(Request $request): array
    {
        $marksPercentage = round($this->marks_percentage, 2);

        $attributes = [
            'marks_percentage' => $marksPercentage,
            'total_points_earned' => $this->total_points_earned,
            'total_points_allocated' => $this->total_points_allocated,
            'time_taken' => $this->time_taken,
            'no_of_answered_questions' => $this->no_of_answered_questions,
            'no_of_correct_answers' => $this->correct_answers,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        if ($this->relationLoaded('userQuestionnaire')) {
            $attributes['user_id'] = Hashids::encode($this->userQuestionnaire->user_id);
            $attributes['questionnaire_id'] = Hashids::encode($this->userQuestionnaire->questionnaire_id);
        }

        return $attributes;
    }
}

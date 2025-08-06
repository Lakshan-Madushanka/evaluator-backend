<?php

namespace App\Http\Resources;

use App\Models\Question;
use Illuminate\Http\Request;
use TiMacDonald\JsonApi\JsonApiResource;
use Vinkla\Hashids\Facades\Hashids;

/**
 * @mixin Question
 */
class UserQuestionnaireResource extends JsonApiResource
{
    public function toAttributes(Request $request): array
    {
        $attributes = [
            'user_questionnaire_id' => Hashids::encode($this->userQuestionnaireId),
            'attempts' => $this->attempts,
            'expires_at' => $this->expires_at,
            'started_at' => $this->started_at,
            'finished_at' => $this->finished_at,
            'updated_at' => $this->created_at,
            'created_at' => $this->created_at,
        ];

        if (isset($this->answers)) {
            $attributes['answers'] = $this->answers;
        }

        return $attributes;
    }

    protected function toRelationships(Request $request): array
    {
        return [
            'categories' => fn () => CategoryResource::collection($this->categories),
        ];
    }
}

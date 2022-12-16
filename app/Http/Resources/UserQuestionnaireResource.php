<?php

namespace App\Http\Resources;

use App\Models\Question;
use Illuminate\Http\Request;
use TiMacDonald\JsonApi\JsonApiResource;

/**
 * @mixin Question
 */
class UserQuestionnaireResource extends JsonApiResource
{
    public function toAttributes(Request $request): array
    {
        $attributes = [
            'updated_at' => $this->created_at->toFormattedDayDateString(),
            'created_at' => $this->created_at->toFormattedDayDateString(),
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

<?php

namespace App\Http\Resources;

use App\Models\Question;
use Illuminate\Http\Request;
use TiMacDonald\JsonApi\JsonApiResource;

/**
 * @mixin Question
 */
class QuestionResource extends JsonApiResource
{
    public function toAttributes(Request $request): array
    {
        $attributes = [
            'pretty_id' => $this->pretty_id,
            'hardness' => $this->difficulty->name,
            'content' => $this->text,
            'created_at' => $this->created_at->toFormattedDayDateString(),
            'answers_type_single' => $this->is_answers_type_single,
            'no_of_answers' => $this->no_of_answers,
            'completed' => $this->no_of_answers === $this->whenCounted('answers'),
            'no_of_assigned_answers' => $this->whenCounted('answers'),
            'images_count' => $this->whenCounted('images'),
        ];

        return $attributes;
    }

    protected function toRelationships(Request $request): array
    {
        return [
            'categories' => fn () => CategoryResource::collection($this->categories),
        ];
    }
}

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
            'hardness' => $this->difficulty->name,
            'content' => $this->text,
            'created_at' => $this->created_at->toFormattedDayDateString(),
            'no_of_answers' => $this->no_of_answers,
            'completed' => $this->no_of_answers === $this->whenCounted('answers'),
            'no_of_assigned_answers' => $this->whenCounted('answers'),
            'images_count' => $this->whenCounted('images'),
            /*'categories' => $this->relationLoaded('categories') ?
                BasicCategoryResource::collection($this->categories) : null,*/
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

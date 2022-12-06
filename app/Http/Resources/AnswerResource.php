<?php

namespace App\Http\Resources;

use App\Models\Answer;
use Illuminate\Http\Request;
use TiMacDonald\JsonApi\JsonApiResource;

/**
 * @mixin Answer
 */
class AnswerResource extends JsonApiResource
{
    public function toAttributes(Request $request): array
    {
        $attributes = [
            'text' => $this->text,
            'created_at' => $this->created_at->toFormattedDayDateString(),
            'images_count' => $this->images_count ?? 0,
        ];

        return $attributes;
    }

    protected function toRelationships(Request $request): array
    {
        return [];
    }
}
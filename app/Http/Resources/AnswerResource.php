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
            'images' => $this->relationLoaded('categories') ?
                MediaResource::collection($this->images) : null,
        ];

        return $attributes;
    }

    protected function toRelationships(Request $request): array
    {
        return [];
    }
}

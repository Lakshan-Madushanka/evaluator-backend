<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @mixin Media
 */
class MediaResource extends JsonResource
{
    /**
     * @param $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->uuid,
            'name' => $this->name,
            'file_name' => $this->file_name,
            'collection' => $this->collection_name,
            'original_url' => $this->original_url,
            'size' => $this->size,
            'created_at' => $this->created_at,
            'order_column' => $this->order_column,
        ];
    }
}

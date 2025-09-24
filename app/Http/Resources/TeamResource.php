<?php

namespace App\Http\Resources;

use App\Models\Team;
use Illuminate\Http\Request;
use TiMacDonald\JsonApi\JsonApiResource;

/**
 * @mixin Team
 */
class TeamResource extends JsonApiResource
{
    public function toAttributes(Request $request): array
    {
        return [
            'name' => $this->name,
            'created_at' => $this->created_at,
        ];
    }
}

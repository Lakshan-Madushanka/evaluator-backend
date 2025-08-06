<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Vinkla\Hashids\Facades\Hashids;

trait HasHashids
{
    public function hashId(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Hashids::encode($this->getKey())
        );
    }
}

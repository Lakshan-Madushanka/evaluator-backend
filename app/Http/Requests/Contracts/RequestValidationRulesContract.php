<?php

namespace App\Http\Requests\Contracts;

interface RequestValidationRulesContract
{
    /**
     * @return array<string, mixed>
     */
    public static function getRules(): array;
}

<?php

namespace Tests\Repositories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Collection;

class TeamRepository
{
    public static function getRandomTeam(): Team
    {
        return Team::inRandomOrder()->first();
    }

    public static function getTotalTeamsCount(): int
    {
        return Team::count();
    }

    public static function getRandomTeams(int $limit = 10): Collection
    {
        return Team::inRandomOrder()->limit($limit)->get();
    }
}

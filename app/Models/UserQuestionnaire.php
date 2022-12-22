<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserQuestionnaire
 *
 * @method static Builder|UserQuestionnaire newModelQuery()
 * @method static Builder|UserQuestionnaire newQuery()
 * @method static Builder|UserQuestionnaire query()
 * @mixin Eloquent
 */
class UserQuestionnaire extends Model
{
    protected $table = 'user_questionnaire';

    /**
     * @var string[]
     */
    protected $fillable = [
        'questionnaire_id',
        'time_taken',
        'correct_answers',
        'no_of_answered_questions',
        'marks',
    ];

    //-----------------------------------scopes-------------------------------------------------------------------------

    public function scopeAvailable(Builder $query, string $code): Builder
    {
        return $query
            ->where('code', $code)
            ->where('attempts', 0)
            ->where('expires_at', '>=', now());
    }

    //-----------------------------------scopes-------------------------------------------------------------------------
}

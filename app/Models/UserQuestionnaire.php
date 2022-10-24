<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserQuestionnaire
 *
 * @method static \Illuminate\Database\Eloquent\Builder|UserQuestionnaire newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserQuestionnaire newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserQuestionnaire query()
 * @mixin \Eloquent
 */
class UserQuestionnaire extends Model
{
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
}

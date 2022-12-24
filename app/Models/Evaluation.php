<?php

namespace App\Models;

use App\Models\Concerns\HasHashids;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Evaluation
 *
 * @property int $id
 * @property int $user_questionnaire_id
 * @property int $time_taken
 * @property int $correct_answers
 * @property int $no_of_answered_questions
 * @property int $marks
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder|Evaluation newModelQuery()
 * @method static Builder|Evaluation newQuery()
 * @method static Builder|Evaluation query()
 * @method static Builder|Evaluation whereCorrectAnswers($value)
 * @method static Builder|Evaluation whereCreatedAt($value)
 * @method static Builder|Evaluation whereId($value)
 * @method static Builder|Evaluation whereMarks($value)
 * @method static Builder|Evaluation whereNoOfAnsweredQuestions($value)
 * @method static Builder|Evaluation whereTimeTaken($value)
 * @method static Builder|Evaluation whereUpdatedAt($value)
 * @method static Builder|Evaluation whereUserQuestionnaireId($value)
 *
 * @mixin Eloquent
 */
class Evaluation extends Model
{
    use HasFactory;
    use HasHashids;

    protected $fillable = [
        'user_questionnaire_id',
        'time_taken',
        'correct_answers',
        'no_of_answered_questions',
        'marks_percentage',
        'total_points_earned',
        'total_points_allocated',
    ];
}

<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Difficulty;
use App\Models\Concerns\HasHashids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * App\Models\Questionnaire
 *
 * @property int $id
 * @property string $content
 * @property Difficulty $difficulty
 * @property int $no_of_questions
 * @property int $no_of_easy_questions
 * @property int $no_of_medium_questions
 * @property int $no_of_hard_questions
 * @property int $allocated_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Category[] $categories
 * @property-read int|null $categories_count
 * @property-read \App\Models\UserQuestionnaire|null $evaluations
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Question[] $questions
 * @property-read int|null $questions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 *
 * @method static \Database\Factories\QuestionnaireFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Questionnaire newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Questionnaire newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Questionnaire query()
 * @method static \Illuminate\Database\Eloquent\Builder|Questionnaire whereAllocatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questionnaire whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questionnaire whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questionnaire whereDifficulty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questionnaire whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questionnaire whereNoOfEasyQuestions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questionnaire whereNoOfHardQuestions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questionnaire whereNoOfMediumQuestions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questionnaire whereNoOfQuestions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questionnaire whereUpdatedAt($value)
 * @mixin \Eloquent
 *
 * @property string $name
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Questionnaire whereName($value)
 */
class Questionnaire extends Model
{
    use HasFactory;
    use HasHashids;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'difficulty',
        'no_of_questions',
        'no_of_easy_questions',
        'no_of_medium_questions',
        'no_of_hard_questions',
        'allocated_time',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'difficulty' => Difficulty::class,
    ];

    //--------------------------Relationships----------------------------
    /**
     * @return MorphToMany<Category>
     */
    public function categories(): MorphToMany
    {
        return $this->morphToMany(
            related: Category::class,
            name: 'categorizable',
            table: 'categorizables',
            relatedPivotKey: 'category_id'
        );
    }

    /**
     * @return BelongsToMany<Question>
     */
    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Question::class,
            table: 'questionnaire_question',
            foreignPivotKey: 'questionnaire_id',
            relatedPivotKey: 'question_id'
        );
    }

    /**
     * @return BelongsToMany<User>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            related: User::class,
            table: 'user_questionnaire',
            foreignPivotKey: 'questionnaire_id',
            relatedPivotKey: 'user_id',
        );
    }

    /**
     * @return HasOne<UserQuestionnaire>
     */
    public function evaluations(): HasOne
    {
        return $this->hasOne(
            related: UserQuestionnaire::class,
            foreignKey: 'user_questionnaire_id',
        );
    }

    //-------------------------End of Relationships----------------------------
}

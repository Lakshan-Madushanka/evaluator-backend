<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Difficulty;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * App\Models\Question
 *
 * @property int $id
 * @property Difficulty $difficulty
 * @property string $text
 * @property int $no_of_answers
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Answer[] $answers
 * @property-read int|null $answers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Category[] $categories
 * @property-read int|null $categories_count
 *
 * @method static \Database\Factories\QuestionFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Question newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Question newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Question query()
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereDifficulty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereNoOfAnswers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Question extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'difficulty',
        'text',
        'no_of_answers',
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
     * @return BelongsToMany<Answer>
     */
    public function answers(): BelongsToMany
    {
        return $this->belongsToMany(
            Answer::class,
            'question_answer',
            foreignPivotKey: 'question_id',
            relatedPivotKey: 'answer_id'
        );
    }

    //-------------------------End of Relationships----------------------------
}

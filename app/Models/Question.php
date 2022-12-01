<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Difficulty;
use App\Models\Concerns\HasHashids;
use Database\Factories\QuestionFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Models\Question
 *
 * @property int $id
 * @property Difficulty $difficulty
 * @property string $text
 * @property int $no_of_answers
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Answer[] $answers
 * @property-read int|null $answers_count
 * @property-read Collection|Category[] $categories
 * @property-read int|null $categories_count
 *
 * @method static QuestionFactory factory(...$parameters)
 * @method static Builder|Question newModelQuery()
 * @method static Builder|Question newQuery()
 * @method static Builder|Question query()
 * @method static Builder|Question whereCreatedAt($value)
 * @method static Builder|Question whereDifficulty($value)
 * @method static Builder|Question whereId($value)
 * @method static Builder|Question whereNoOfAnswers($value)
 * @method static Builder|Question whereText($value)
 * @method static Builder|Question whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Question extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use HasHashids;

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

    /**
     * @return MorphMany<Model>
     */
    public function images(): MorphMany
    {
        return $this->media()->orderBy('order_column');
    }

    //-------------------------End of Relationships----------------------------
}

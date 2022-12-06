<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasHashids;
use App\Services\PrettyIdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Models\Answer
 *
 * @property int $id
 * @property string $text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Database\Factories\AnswerFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Answer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Answer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Answer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Answer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Answer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Answer whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Answer whereUpdatedAt($value)
 * @mixin \Eloquent
 *
 * @property string $pretty_id
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $images
 * @property-read int|null $images_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
 * @property-read int|null $media_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Answer wherePrettyId($value)
 */
class Answer extends Model implements HasMedia
{
    use HasFactory;
    use HasHashids;
    use InteractsWithMedia;

    protected $fillable = [
        'text',
        'pretty_id',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::creating(function (Answer $answer) {
            $answer->pretty_id = PrettyIdGenerator::generate('answers', 'ans_', 12);
        });
    }
    //--------------------------Relationships----------------------------

    /**
     * @return MorphMany<Answer>
     */
    public function images(): MorphMany
    {
        return $this->media()->orderBy('order_column');
    }

    //------------------------End Of Relationships----------------------------
}

<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Answer
 *
 * @property int $id
 * @property string $text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\AnswerFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Answer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Answer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Answer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Answer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Answer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Answer whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Answer whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $pretty_id
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $images
 * @property-read int|null $images_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
 * @property-read int|null $media_count
 * @method static \Illuminate\Database\Eloquent\Builder|Answer wherePrettyId($value)
 * @property-read mixed $hash_id
 */
	class Answer extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * App\Models\Category
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Questionnaire[] $questionnaires
 * @property-read int|null $questionnaires_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Question[] $questions
 * @property-read int|null $questions_count
 * @method static \Database\Factories\CategoryFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read mixed $hash_id
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
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
 * @mixin Eloquent
 * @property float|null $marks_percentage
 * @property float|null $total_points_earned
 * @property float|null $total_points_allocated
 * @property-read mixed $hash_id
 * @property-read \App\Models\Questionnaire|null $questionnaire
 * @property-read \App\Models\User|null $user
 * @property-read \App\Models\UserQuestionnaire $userQuestionnaire
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evaluation filterByQuestionnaireId(string $value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evaluation filterByUserId(string $value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evaluation filterByUserQuestionnaire(...$value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evaluation whereMarksPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evaluation whereTotalPointsAllocated($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evaluation whereTotalPointsEarned($value)
 */
	class Evaluation extends \Eloquent {}
}

namespace App\Models{
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
 * @property-read MediaCollection|Media[] $images
 * @property-read int|null $images_count
 * @property-read MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @method static Builder|Question completed()
 * @property string $pretty_id
 * @property bool $is_answers_type_single
 * @property-read Collection|\App\Models\Answer[] $onlyAnswers
 * @property-read int|null $only_answers_count
 * @method static Builder|Question eligible(\App\Models\Questionnaire $questionnaire)
 * @method static Builder|Question whereIsAnswersTypeSingle($value)
 * @method static Builder|Question wherePrettyId($value)
 * @property-read mixed $hash_id
 */
	class Question extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Category[] $categories
 * @property-read int|null $categories_count
 * @property-read UserQuestionnaire|null $evaluations
 * @property-read Collection|Question[] $questions
 * @property-read int|null $questions_count
 * @property-read Collection|User[] $users
 * @property-read int|null $users_count
 * @method static QuestionnaireFactory factory(...$parameters)
 * @method static Builder|Questionnaire newModelQuery()
 * @method static Builder|Questionnaire newQuery()
 * @method static Builder|Questionnaire query()
 * @method static Builder|Questionnaire whereAllocatedTime($value)
 * @method static Builder|Questionnaire whereContent($value)
 * @method static Builder|Questionnaire whereCreatedAt($value)
 * @method static Builder|Questionnaire whereDifficulty($value)
 * @method static Builder|Questionnaire whereId($value)
 * @method static Builder|Questionnaire whereNoOfEasyQuestions($value)
 * @method static Builder|Questionnaire whereNoOfHardQuestions($value)
 * @method static Builder|Questionnaire whereNoOfMediumQuestions($value)
 * @method static Builder|Questionnaire whereNoOfQuestions($value)
 * @method static Builder|Questionnaire whereUpdatedAt($value)
 * @mixin Eloquent
 * @property string $name
 * @method static Builder|Questionnaire whereName($value)
 * @property bool $single_answers_type
 * @property-read Collection|\App\Models\Question[] $questionsWithPivotData
 * @property-read int|null $questions_with_pivot_data_count
 * @method static Builder|Questionnaire completed($value)
 * @method static Builder|Questionnaire whereSingleAnswersType($value)
 * @property-read mixed $hash_id
 */
	class Questionnaire extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read mixed $hash_id
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team query()
 */
	class Team extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property Role $role
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Questionnaire[] $questionnaires
 * @property-read int|null $questionnaires_count
 * @property-read Collection|Questionnaire[] $questionnairesWithAnswers
 * @property-read int|null $questionnaires_with_answers_count
 * @method static UserFactory factory(...$parameters)
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereRole($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read Collection|Questionnaire[] $questionnairesWithPivotData
 * @property-read int|null $questionnaires_with_pivot_data_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Evaluation> $evaluations
 * @property-read int|null $evaluations_count
 * @property-read mixed $hash_id
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UserQuestionnaire
 *
 * @property int $id
 * @property int $questionnaire_id
 * @property int $user_id
 * @property int $attempts
 * @property string|null $code
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $finished_at
 * @property \Illuminate\Support\Carbon $expires_at
 * @property array|null $answers
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Evaluation|null $evaluation
 * @method static Builder|UserQuestionnaire available(string $code)
 * @method static Builder|UserQuestionnaire checkAvailable()
 * @method static Builder|UserQuestionnaire expired($expired = false)
 * @method static Builder|UserQuestionnaire newModelQuery()
 * @method static Builder|UserQuestionnaire newQuery()
 * @method static Builder|UserQuestionnaire query()
 * @method static Builder|UserQuestionnaire whereAnswers($value)
 * @method static Builder|UserQuestionnaire whereAttempts($value)
 * @method static Builder|UserQuestionnaire whereCode($value)
 * @method static Builder|UserQuestionnaire whereCreatedAt($value)
 * @method static Builder|UserQuestionnaire whereExpiresAt($value)
 * @method static Builder|UserQuestionnaire whereFinishedAt($value)
 * @method static Builder|UserQuestionnaire whereId($value)
 * @method static Builder|UserQuestionnaire whereQuestionnaireId($value)
 * @method static Builder|UserQuestionnaire whereStartedAt($value)
 * @method static Builder|UserQuestionnaire whereUpdatedAt($value)
 * @method static Builder|UserQuestionnaire whereUserId($value)
 * @mixin Eloquent
 * @property-read mixed $hash_id
 */
	class UserQuestionnaire extends \Eloquent {}
}


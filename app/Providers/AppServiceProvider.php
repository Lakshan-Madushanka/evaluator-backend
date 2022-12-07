<?php

namespace App\Providers;

use App\Models\Answer;
use App\Models\Category;
use App\Models\Question;
use App\Models\Questionnaire;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use TiMacDonald\JsonApi\JsonApiResource;
use Vinkla\Hashids\Facades\Hashids;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerLocally();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->customizePolymorphicTypes();
        $this->setupPasswordRules();
        $this->customizeJsonApiId();
        $this->implicitRouteModelBinding();
    }

    public function registerLocally(): void
    {
        if ($this->app->environment('local')) {
            $this->registerTelescope();
            //$this->handleExceedingCumulativeQueryDuration();

            Model::shouldBeStrict();
        }
    }

    public function registerTelescope(): void
    {
        $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
        $this->app->register(TelescopeServiceProvider::class);
    }

    private function handleExceedingCumulativeQueryDuration(): void
    {
        if ($this->app->runningInConsole()) {
            return;
        }

        DB::listen(static function (QueryExecuted $event) {
            if ($event->time > 500) {
                throw new QueryException(
                    $event->sql,
                    $event->bindings,
                    new Exception('Individual database query exceeded 500ms.')
                );
            }
        });
    }

    public function customizePolymorphicTypes(): void
    {
        Relation::enforceMorphMap([
            1 => Questionnaire::class,
            2 => Question::class,
            3 => Answer::class,
        ]);
    }

    public function setupPasswordRules(): void
    {
        Password::defaults(function () {
            $rule = Password::min(8)
                ->mixedCase()
                ->numbers()
                ->letters()
                ->symbols()
                ->uncompromised();

            return $this->app->isProduction()
                ? $rule
                : Password::min(8);
        });
    }

    public function customizeJsonApiId(): void
    {
        JsonApiResource::resolveIdUsing(function (mixed $resource, Request $request): string {
            return $resource->hash_id;
        });
    }

    public function implicitRouteModelBinding(): void
    {
        \Route::bind('user', function ($value) {
            $id = Hashids::decode($value)[0] ?? PHP_INT_MIN;

            return User::findOrFail($id);
        });

        \Route::bind('category', function ($value) {
            $id = Hashids::decode($value)[0] ?? PHP_INT_MIN;

            return Category::findOrFail($id);
        });

        \Route::bind('question', function ($value) {
            $id = Hashids::decode($value)[0] ?? PHP_INT_MIN;

            return Question::findOrFail($id);
        });

        \Route::bind('answer', function ($value) {
            $id = Hashids::decode($value)[0] ?? PHP_INT_MIN;

            return Answer::findOrFail($id);
        });

        \Route::bind('questionnaire', function ($value) {
            $id = Hashids::decode($value)[0] ?? PHP_INT_MIN;

            return Questionnaire::findOrFail($id);
        });
    }
}

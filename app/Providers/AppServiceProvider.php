<?php

namespace App\Providers;

use App\Models\Question;
use App\Models\Questionnaire;
use Exception;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

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
    }

    public function registerLocally(): void
    {
        if ($this->app->environment('local')) {
            $this->registerTelescope();
            $this->handleExceedingCumulativeQueryDuration();
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
            if ($event->time > 100) {
                throw new QueryException(
                    $event->sql,
                    $event->bindings,
                    new Exception('Individual database query exceeded 100ms.')
                );
            }
        });
    }

    public function customizePolymorphicTypes(): void
    {
        Relation::enforceMorphMap([
            1 => Questionnaire::class,
            2 => Question::class,
        ]);
    }
}

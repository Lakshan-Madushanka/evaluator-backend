<?php

use App\Models\Answer;
use App\Models\Category;
use App\Models\Question;
use Database\Data\Data;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\withoutExceptionHandling;

afterEach(function () {
    $this->seed = true;

    Artisan::call('migrate:fresh', ['--force' => true]);
    Artisan::call('db:seed', ['--force' => true]);
});

it('can build questions', function () {
    withoutExceptionHandling();
    $this->seed = false;

    Data::seedQuestions();

    assertDatabaseCount((new Question())->getTable(), 11);
    assertDatabaseCount((new Answer())->getTable(), 44);
    assertDatabaseCount((new Category())->getTable(), 1);
    assertDatabaseCount('categorizables', 11);
});

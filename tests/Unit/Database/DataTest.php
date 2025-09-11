<?php

use App\Models\Answer;
use App\Models\Category;
use App\Models\Question;
use Database\Data\Data;

use function Pest\Laravel\assertDatabaseCount;

afterEach(function () {
    $this->seed = true;

    Artisan::call('migrate:fresh', ['--force' => true]);
    Artisan::call('db:seed', ['--force' => true]);
});

it('can build questions', function () {
    $this->seed = false;

    Data::seedQuestions();

    assertDatabaseCount((new Question)->getTable(), 30);
    assertDatabaseCount((new Answer)->getTable(), 120);
    assertDatabaseCount((new Category)->getTable(), 2);
    assertDatabaseCount('categorizables', 62);
});

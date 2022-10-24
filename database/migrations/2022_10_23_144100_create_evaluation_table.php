<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluation', function (Blueprint $table) {
            $table->id();

            $table->unsignedSmallInteger('questionnaire_id');

            $table->unsignedSmallInteger('time_taken');
            $table->smallInteger('correct_answers');
            $table->smallInteger('no_of_answered_questions');
            $table->tinyInteger('marks');

            $table->timestamps();

            $table->foreign('questionnaire_id')->on('questionnaires')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evaluation');
    }
};

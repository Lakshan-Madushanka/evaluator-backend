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
        Schema::create('user_questionnaire', function (Blueprint $table) {
            $table->id();

            $table->unsignedSmallInteger('questionnaire_id');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->json('answers');

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
        Schema::dropIfExists('user_questionnaire');
    }
};

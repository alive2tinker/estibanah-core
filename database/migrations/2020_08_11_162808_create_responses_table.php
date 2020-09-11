<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('responses', function (Blueprint $table) {
            $table->id();
            $table->longText('value');
            $table->unsignedBigInteger('question_id');
            $table->foreign('question_id')->references('id')->on('questions');
            $table->unsignedBigInteger('form_response_id');
            $table->foreign('form_response_id')->references('id')->on('form_responses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('responses');
    }
}

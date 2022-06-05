<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVacancyCandidate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacancy_candidate', function (Blueprint $table) {
            $table->foreignId('vacancy_id')->references('id')->on('vacancy');
            $table->foreignId('candidate_id')->references('id')->on('candidate');
            $table->enum('status', [ 'In progress', 'end' ])->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vacancy_candidate');
    }
}

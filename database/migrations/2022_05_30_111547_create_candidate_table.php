<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('second_name');
            $table->string('patronymic');
            $table->string('city');
            $table->string('phone_number');
            $table->string('email');
            $table->string('desired_position');
            $table->decimal('desired_income', $percision = 15, $scale = 2);
            $table->string('work_experience');
            $table->foreignId('vacancy_id')->references('id')->on('vacancy');
            $table->enum('status', ['-1', '0', '1'])->default('0');
            // $table->foreignId('photo_id')->nullable(true)->references('id')->on('');
            // $table->foreignId('summary')->references('id')->on('');

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
        Schema::dropIfExists('candidate');
    }
}

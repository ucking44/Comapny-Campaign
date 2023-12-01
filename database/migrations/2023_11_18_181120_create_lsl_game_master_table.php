<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLslGameMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lsl_game_master', function (Blueprint $table) {
            $table->bigIncrements('Game_id');
            $table->unsignedBigInteger('Create_user_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Update_user_id')->nullable(); // NOT NULL
            $table->string('Game_name')->nullable();
            $table->longText('Description')->nullable();
            $table->bigInteger('Game_image_flag')->nullable();
            $table->bigInteger('Time_based_flag')->default(0)->nullable();
            $table->bigInteger('Moves_based_flag')->default(0)->nullable();
            $table->bigInteger('Score_based_flag')->default(0)->nullable();
            $table->dateTime('Create_date')->nullable();
            $table->dateTime('Update_date')->nullable();
            $table->foreign('Create_user_id')->references('Enrollment_id')->on('lsl_enrollment_master')->onDelete('cascade');
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
        Schema::dropIfExists('lsl_game_master');
    }
}

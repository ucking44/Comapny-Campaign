<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLslGameCompanyMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lsl_game_company_master', function (Blueprint $table) {
            $table->bigIncrements('Company_game_id');
            $table->unsignedBigInteger('Company_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Create_user_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Update_user_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Game_id')->nullable(); // NOT NULL
            $table->bigInteger('Link_to_game_campaign')->nullable();
            $table->bigInteger('Game_for_fun')->default(0)->nullable();
            $table->bigInteger('Game_for_competition')->default(0)->nullable();
            $table->bigInteger('Total_game_winner')->default(0)->nullable();
            $table->bigInteger('Competition_winner_award')->default(0)->nullable();
            $table->bigInteger('Lives_flag')->default(0)->nullable();
            $table->bigInteger('Initial_game_lives')->default(0)->nullable();
            $table->double('Points_value_for_one', 25, 2)->default(0)->nullable();
            $table->bigInteger('Active_flag')->default(0)->nullable();
            $table->dateTime('Competition_start_date')->nullable();
            $table->dateTime('Competition_end_date')->nullable();
            $table->dateTime('Create_date')->nullable();
            $table->dateTime('Update_date')->nullable();
            $table->foreign('Company_id')->references('Company_id')->on('lsl_company_master')->onDelete('cascade');
            $table->foreign('Create_user_id')->references('Enrollment_id')->on('lsl_enrollment_master')->onDelete('cascade');
            $table->foreign('Game_id')->references('Game_id')->on('lsl_game_master')->onDelete('cascade');
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
        Schema::dropIfExists('lsl_game_company_master');
    }
}

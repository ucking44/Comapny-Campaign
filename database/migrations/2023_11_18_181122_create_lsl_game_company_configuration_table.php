<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLslGameCompanyConfigurationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lsl_game_company_configuration', function (Blueprint $table) {
            $table->bigIncrements('Game_configuration_id');
            $table->unsignedBigInteger('Company_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Create_user_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Update_user_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Game_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Company_game_id')->nullable(); // NOT NULL
            $table->bigInteger('Game_level')->default(0)->nullable();
            $table->bigInteger('Total_moves')->default(0)->nullable();
            $table->bigInteger('Issued_lives')->default(0)->nullable();
            $table->bigInteger('Game_matrix')->default(0)->nullable();
            $table->string('Game_image')->nullable();
            $table->date('Time_for_level')->nullable();
            $table->dateTime('Create_date')->nullable();
            $table->dateTime('Update_date')->nullable();
            $table->foreign('Company_id')->references('Company_id')->on('lsl_company_master')->onDelete('cascade');
            $table->foreign('Create_user_id')->references('Enrollment_id')->on('lsl_enrollment_master')->onDelete('cascade');
            $table->foreign('Game_id')->references('Game_id')->on('lsl_game_master')->onDelete('cascade');
            $table->foreign('Company_game_id')->references('Company_game_id')->on('lsl_game_company_master')->onDelete('cascade');
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
        Schema::dropIfExists('lsl_game_company_configuration');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLslGameMemberLivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lsl_game_member_lives', function (Blueprint $table) {
            $table->bigIncrements('Child_id');
            $table->unsignedBigInteger('Company_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Enrollment_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Game_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Company_game_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Enrollment_id2')->nullable(); // NOT NULL
            $table->bigInteger('Game_level')->default(0)->nullable();
            $table->bigInteger('Lives_for_level')->default(0)->nullable();
            $table->bigInteger('Transfer_lives')->default(0)->nullable();
            $table->foreign('Company_id')->references('Company_id')->on('lsl_company_master')->onDelete('cascade');
            $table->foreign('Enrollment_id')->references('Enrollment_id')->on('lsl_enrollment_master')->onDelete('cascade');
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
        Schema::dropIfExists('lsl_game_member_lives');
    }
}

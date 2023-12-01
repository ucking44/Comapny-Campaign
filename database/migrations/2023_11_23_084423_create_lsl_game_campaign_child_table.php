<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLslGameCampaignChildTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lsl_game_campaign_child', function (Blueprint $table) {
            $table->bigIncrements('Child_id');
            $table->unsignedBigInteger('Company_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Campaign_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Game_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Company_game_id')->nullable(); // NOT NULL
            $table->bigInteger('Game_level')->default(0)->nullable();
            $table->double('Reward_points', 25, 2)->default(0)->nullable();
            $table->foreign('Company_id')->references('Company_id')->on('lsl_company_master')->onDelete('cascade');
            $table->foreign('Campaign_id')->references('Campaign_id')->on('lsl_campaign_master')->onDelete('cascade');
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
        Schema::dropIfExists('lsl_game_campaign_child');
    }
}

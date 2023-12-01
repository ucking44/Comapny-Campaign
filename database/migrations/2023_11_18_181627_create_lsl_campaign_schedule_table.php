<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLslCampaignScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lsl_campaign_schedule', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('Campaign_id')->nullable(); // NOT NULL
            $table->bigInteger('Jan')->default(0)->nullable(); // NOT NULL
            $table->bigInteger('Feb')->default(0)->nullable(); // NOT NULL
            $table->bigInteger('Mar')->default(0)->nullable(); // NOT NULL
            $table->bigInteger('Apr')->default(0)->nullable(); // NOT NULL
            $table->bigInteger('May')->default(0)->nullable(); // NOT NULL
            $table->bigInteger('Jun')->default(0)->nullable(); // NOT NULL
            $table->bigInteger('Jul')->default(0)->nullable(); // NOT NULL
            $table->bigInteger('Aug')->default(0)->nullable(); // NOT NULL
            $table->bigInteger('Sep')->default(0)->nullable(); // NOT NULL
            $table->bigInteger('Oct')->default(0)->nullable(); // NOT NULL
            $table->bigInteger('Nov')->default(0)->nullable(); // NOT NULL
            $table->bigInteger('Dec')->default(0)->nullable(); // NOT NULL
            $table->bigInteger('Mon')->default(0)->nullable(); // NOT NULL
            $table->bigInteger('Tue')->default(0)->nullable(); // NOT NULL
            $table->bigInteger('Wed')->default(0)->nullable(); // NOT NULL
            $table->bigInteger('Thu')->default(0)->nullable(); // NOT NULL
            $table->bigInteger('Fri')->default(0)->nullable(); // NOT NULL
            $table->bigInteger('Sat')->default(0)->nullable(); // NOT NULL
            $table->bigInteger('Sun')->default(0)->nullable(); // NOT NULL
            $table->bigInteger('First_week')->default(0)->nullable(); // NOT NULL
            $table->bigInteger('Second_week')->default(0)->nullable(); // NOT NULL
            $table->bigInteger('Third_week')->default(0)->nullable(); // NOT NULL
            $table->bigInteger('Fourth_week')->default(0)->nullable(); // NOT NULL
            $table->time('Start_time')->nullable(); // NOT NULL
            $table->time('End_time')->nullable();  // NOT NULL
            $table->foreign('Campaign_id')->references('Campaign_id')->on('lsl_campaign_master')->onDelete('cascade');
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
        Schema::dropIfExists('lsl_campaign_schedule');
    }
}

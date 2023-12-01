<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLslCampaignChild2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lsl_campaign_child2', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('Company_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Campaign_id')->nullable(); // NOT NULL
            $table->string('Campaign_name')->nullable();  // NOT NULL
            $table->bigInteger('Transaction_id')->nullable(); // NOT NULL
            $table->bigInteger('Transaction_channel_id')->nullable(); // NOT NULL
            $table->bigInteger('Campaign_type')->nullable(); // NOT NULL
            $table->bigInteger('Campaign_sub_type')->nullable(); // NOT NULL
            $table->foreign('Company_id')->references('Company_id')->on('lsl_company_master')->onDelete('cascade');
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
        Schema::dropIfExists('lsl_campaign_child2');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLslPromoCampaignTmpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lsl_promo_campaign_tmp', function (Blueprint $table) {
            $table->bigIncrements('Campaign_id');
            $table->unsignedBigInteger('Company_id')->nullable(); // NOT NULL
            $table->double('Points', 25, 0)->nullable();
            $table->string('File_name')->nullable();
            $table->string('Promo_code')->nullable();
            $table->date('Start_date')->nullable();
            $table->date('End_date')->nullable();
            $table->foreign('Company_id')->references('Company_id')->on('lsl_company_master')->onDelete('cascade');
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
        Schema::dropIfExists('lsl_promo_campaign_tmp');
    }
}

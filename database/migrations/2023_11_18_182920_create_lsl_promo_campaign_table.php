<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLslPromoCampaignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lsl_promo_campaign', function (Blueprint $table) {
            $table->bigIncrements('Campaign_id');
            $table->unsignedBigInteger('Company_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Loyalty_program_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Sweepstake_id')->nullable(); // NOT NULL
            $table->bigInteger('Sweepstake_flag')->default(0)->nullable();  // NOT NULL                        Changed on Sunday  default('No');
            $table->bigInteger('Sweepstake_ticket_limit')->default(0)->nullable();;  // NOT NULL
            $table->date('Date')->nullable();
            $table->date('Start_date')->nullable();
            $table->date('End_date')->nullable();
            $table->double('Points', 25, 0)->nullable();
            $table->string('File_name')->nullable();
            $table->string('Promo_code')->nullable();
            $table->string('Campaign_description')->nullable();
            $table->bigInteger('Promo_code_status')->default(1)->nullable();
            $table->bigInteger('Active_flag')->default(1)->nullable();
            $table->string('campaign_status')->default('Disabled')->nullable();
            $table->foreign('Company_id')->references('Company_id')->on('lsl_company_master')->onDelete('cascade');
            $table->foreign('Loyalty_program_id')->references('Loyalty_program_id')->on('lsl_loyalty_program_master')->onDelete('cascade');
            $table->foreign('Sweepstake_id')->references('Sweepstake_id')->on('lsl_sweepstake_master')->onDelete('cascade');
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
        Schema::dropIfExists('lsl_promo_campaign');
    }
}

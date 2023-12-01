<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLslTierMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lsl_tier_master', function (Blueprint $table) {
            $table->bigIncrements('Tier_id');
            $table->unsignedBigInteger('Company_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Create_User_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Update_User_id')->nullable();
            $table->unsignedBigInteger('Tier_level_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Upgrade_criteria')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Retention_criteria')->nullable(); // NOT NULL
            $table->string('Tier_name')->nullable();  // NOT NULL
            $table->bigInteger('Tier_period')->nullable(); // NOT NULL
            $table->bigInteger('Upgrade_criteria_value')->nullable(); // NOT NULL
            $table->string('Redeemption_level')->nullable();  // NOT NULL
            $table->double('minimum_points_balance', 25, 0)->nullable();  // NOT NULL
            $table->double('minimum_points_balance_redeem', 25, 0)->nullable();  // NOT NULL
            $table->string('Tier_invitation')->nullable();  // NOT NULL
            $table->dateTime('Creation_date')->nullable();  // NOT NULL
            $table->dateTime('Update_date')->nullable();
            $table->foreign('Company_id')->references('Company_id')->on('lsl_company_master')->onDelete('cascade');
            $table->foreign('Create_User_id')->references('Enrollment_id')->on('lsl_enrollment_master')->onDelete('cascade');
            $table->foreign('Tier_level_id')->references('Tier_level_id')->on('lsl_tier_level_master')->onDelete('cascade');
            $table->foreign('Upgrade_criteria')->references('Tier_criteria_id')->on('lsl_tier_criteria_master')->onDelete('cascade');
            //$table->foreign('Retention_criteria')->references('Tier_criteria_id')->on('lsl_tier_criteria_master')->onDelete('cascade');
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
        Schema::dropIfExists('lsl_tier_master');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLslTierCriteriaMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lsl_tier_criteria_master', function (Blueprint $table) {
            $table->bigIncrements('Tier_criteria_id');
            $table->string('Tier_criteria_name')->nullable();  // NOT NULL
            $table->string('Criteria_description')->nullable();  // NOT NULL
            $table->unsignedBigInteger('Create_User_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Update_User_id')->nullable();
            $table->dateTime('Create_date')->nullable();  // NOT NULL
            $table->dateTime('Update_date')->nullable();
            $table->foreign('Create_User_id')->references('Enrollment_id')->on('lsl_enrollment_master')->onDelete('cascade');
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
        Schema::dropIfExists('lsl_tier_criteria_master');
    }
}

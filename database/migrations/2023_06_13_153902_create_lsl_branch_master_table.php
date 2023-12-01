<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLslBranchMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lsl_branch_master', function (Blueprint $table) {
            $table->bigIncrements('branch_id');
            $table->unsignedBigInteger('Company_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Partner_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Create_User_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Update_User_id')->nullable();
            $table->string('branch_code')->nullable();
            $table->string('branch_name')->nullable();
            $table->text('branch_address')->nullable();
            $table->unsignedBigInteger('country_currency_id')->nullable();  ////  NOT NULL
            $table->unsignedBigInteger('region_id')->nullable();  ////  NOT NULL
            $table->unsignedBigInteger('zone_id')->nullable();  ////  NOT NULL
            $table->unsignedBigInteger('state_id')->nullable();  ////  NOT NULL
            $table->unsignedBigInteger('city_id')->nullable();  ////  NOT NULL
            $table->string('Pos_series_no')->nullable();
            $table->string('Receipt_series_no')->nullable();
            $table->bigInteger('Total_membership_cards')->nullable();
            $table->dateTime('Creation_date')->nullable();
            $table->dateTime('Update_date')->nullable();
            $table->foreign('Company_id')->references('Company_id')->on('lsl_company_master')->onDelete('cascade');
            $table->foreign('Create_User_id')->references('Enrollment_id')->on('lsl_enrollment_master')->onDelete('cascade');
            $table->foreign('Partner_id')->references('Partner_id')->on('lsl_partner_master')->onDelete('cascade');
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
        Schema::dropIfExists('lsl_branch_master');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLslPartnerMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lsl_partner_master', function (Blueprint $table) {
            $table->bigIncrements('Partner_id');
            $table->unsignedBigInteger('Company_id')->nullable();
            $table->unsignedBigInteger('Create_User_id')->nullable();
            $table->unsignedBigInteger('Update_User_id')->nullable();
            $table->unsignedBigInteger('Partner_category_id')->nullable();
            $table->bigInteger('Partner_type')->nullable();
            $table->string('Partner_name')->nullable();
            $table->longText('Partner_address')->nullable();
            $table->string('Partner_contact_person_name')->nullable();
            $table->string('Partner_contact_person_email')->nullable();
            $table->string('Partner_logo')->nullable();
            $table->string('Corporate_email')->nullable();
            $table->dateTime('Create_date')->nullable();
            $table->dateTime('Update_date')->nullable();
            $table->foreign('Company_id')->references('Company_id')->on('lsl_company_master')->onDelete('cascade');
            $table->foreign('Create_User_id')->references('Enrollment_id')->on('lsl_enrollment_master')->onDelete('cascade');
            $table->foreign('Partner_category_id')->references('Partner_category_id')->on('lsl_partner_category')->onDelete('cascade');
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
        Schema::dropIfExists('lsl_partner_master');
    }
}

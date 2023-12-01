<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLslPartnerCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lsl_partner_category', function (Blueprint $table) {
            $table->bigIncrements('Partner_category_id');
            $table->unsignedBigInteger('Create_User_id')->nullable();
            $table->unsignedBigInteger('Update_User_id')->nullable();
            $table->string('Partner_category_name')->nullable();
            $table->bigInteger('Active_flag')->default(0);
            $table->dateTime('Creation_date')->nullable();
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
        Schema::dropIfExists('lsl_partner_category');
    }
}

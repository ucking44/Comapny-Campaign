<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLslSweepstakeMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lsl_sweepstake_master', function (Blueprint $table) {
            $table->bigIncrements('Sweepstake_id');
            $table->unsignedBigInteger('Company_id')->nullable();  ////  NOT NULL
            $table->unsignedBigInteger('Create_user_id')->nullable();  ////  NOT NULL
            $table->unsignedBigInteger('Update_user_id')->nullable();  ////  NOT NULL
            $table->string('Sweepstake_name')->nullable(); //NOT NULL
            $table->bigInteger('Winners')->nullable(); //NOT NULL
            $table->double('Prize')->nullable(); //NOT NULL
            $table->longText('Prize_description')->nullable(); //NOT NULL
            $table->string('Prize_image')->nullable(); //NOT NULL
            $table->string('Link_to_campaign')->nullable(); //NOT NULL
            $table->date('From_date')->nullable(); //NOT NULL
            $table->date('To_date')->nullable(); //NOT NULL
            $table->bigInteger('Active_flag')->default(0)->nullable(); //NOT NULL
            $table->dateTime('Create_date')->nullable(); //NOT NULL
            $table->dateTime('Update_date')->nullable(); //NOT NULL
            $table->foreign('Company_id')->references('Company_id')->on('lsl_company_master')->onDelete('cascade');
            $table->foreign('Create_user_id')->references('Enrollment_id')->on('lsl_enrollment_master')->onDelete('cascade');
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
        Schema::dropIfExists('lsl_sweepstake_master');
    }
}

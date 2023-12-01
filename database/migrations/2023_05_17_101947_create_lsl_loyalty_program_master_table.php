<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLslLoyaltyProgramMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lsl_loyalty_program_master', function (Blueprint $table) {
            $table->bigIncrements('Loyalty_program_id');
            $table->unsignedBigInteger('Company_id')->nullable();
            $table->string('Loyalty_program_name')->nullable();
            $table->string('Loyalty_program_logo')->nullable();
            $table->unsignedBigInteger('User_apply_to')->nullable();
            $table->unsignedBigInteger('Create_User_id')->nullable();
            $table->unsignedBigInteger('Update_User_id')->nullable();
            $table->dateTime('Creation_date')->nullable();
            $table->dateTime('Update_date')->nullable();
            $table->bigInteger('Active_flag')->nullable();
            //$table->string('enableOrDisable');
            $table->foreign('Company_id')->references('Company_id')->on('lsl_company_master')->onDelete('cascade');
            $table->timestamps();
            //$table->date('start_date')->nullable();
            //$table->date('end_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lsl_loyalty_program_master');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLslCompanyTransactionChannelMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lsl_company_transaction_channel_master', function (Blueprint $table) {
            $table->bigIncrements('Transaction_channel_id');
            $table->unsignedBigInteger('Company_id')->nullable();  ////  NOT NULL
            $table->unsignedBigInteger('Create_user_id')->nullable();  ////  NOT NULL
            $table->unsignedBigInteger('Update_user_id')->nullable();
            $table->string('Transaction_channel_code')->nullable();  //// NOT NULL
            $table->string('Transaction_channel_name')->nullable();  //// NOT NULL
            $table->dateTime('Create_date')->nullable();  ////  NOT NULL
            $table->dateTime('Update_date')->nullable();  ////  NOT NULL
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
        Schema::dropIfExists('lsl_company_transaction_channel_master');
    }
}

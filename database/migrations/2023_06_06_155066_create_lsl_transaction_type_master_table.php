<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLslTransactionTypeMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lsl_transaction_type_master', function (Blueprint $table) {
            $table->bigIncrements('Transaction_id');
            $table->unsignedBigInteger('Create_user_id')->nullable();  ////  NOT NULL
            $table->unsignedBigInteger('Update_user_id')->nullable();  ////  NOT NULL
            $table->string('Transaction_type_name')->nullable();  //// NOT NULL
            $table->dateTime('Create_date')->nullable();  ////  NOT NULL
            $table->dateTime('Update_date')->nullable();  ////  NOT NULL
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
        Schema::dropIfExists('lsl_transaction_type_master');
    }
}

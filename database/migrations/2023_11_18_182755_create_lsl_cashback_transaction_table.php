<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLslCashbackTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lsl_cashback_transaction', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('Company_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Enrollment_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Branch_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Campaign_id')->nullable(); // NOT NULL
            $table->string('Campaign_name')->nullable();  // NOT NULL
            $table->string('Customer_name')->nullable();  // NOT NULL
            $table->string('Membership_id')->nullable();  // NOT NULL
            $table->double('Account_no', 25, 0)->nullable();  // NOT NULL
            $table->double('Transaction_amount', 25, 2)->nullable();  // NOT NULL
            $table->double('Cashback_amount', 25, 2)->nullable();  // NOT NULL
            $table->foreign('Company_id')->references('Company_id')->on('lsl_company_master')->onDelete('cascade');
            $table->foreign('Enrollment_id')->references('Enrollment_id')->on('lsl_enrollment_master')->onDelete('cascade');
            $table->foreign('Branch_id')->references('branch_id')->on('lsl_branch_master')->onDelete('cascade');
            $table->foreign('Campaign_id')->references('Campaign_id')->on('lsl_campaign_master')->onDelete('cascade');
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
        Schema::dropIfExists('lsl_cashback_transaction');
    }
}

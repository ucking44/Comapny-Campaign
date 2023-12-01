<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLslTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lsl_transaction', function (Blueprint $table) {
            $table->bigIncrements('Transaction_id');
            $table->unsignedBigInteger('Transaction_type_code')->nullable();  ////  NOT NULL
            $table->unsignedBigInteger('Company_transaction_Type')->nullable();  ////  NOT NULL
            $table->unsignedBigInteger('Transaction_channel_code')->nullable();  ////  NOT NULL
            $table->unsignedBigInteger('Company_id')->nullable();  ////  NOT NULL
            $table->unsignedBigInteger('Member1_enroll_id'); //->nullable();  ////  NOT NULL
            $table->string('Membership1_id')->nullable();  //// NOT NULL
            $table->unsignedBigInteger('Branch_user_id')->nullable();  ////  NOT NULL
            $table->string('Branch_user_emailid')->nullable();  ////   NULL
            $table->dateTime('Transaction_date')->nullable();  ////  NOT NULL
            $table->string('Gift_card')->nullable();  ////  NULL
            $table->unsignedBigInteger('Branch_user_pin')->nullable();  ////  NOT NULL
            $table->string('Product_group_code')->nullable();  ////  NOT NULL
            $table->string('Product_brand_code')->nullable();  ////  NOT NULL
            $table->string('Item_code')->nullable();  ////  NOT NULL
            $table->unsignedBigInteger('item_id'); //->nullable();  ////  NOT NULL (I added this column)
            $table->unsignedBigInteger('Quantity'); //->nullable();  ////  NOT NULL
            $table->double('VAT', 25, 2)->nullable(); //  NULL
            $table->double('Transaction_amount', 25, 2)->nullable(); // NOT NULL
            $table->double('Product_cost', 25, 2)->nullable(); // NOT NULL
            $table->double('Redeem_points', 25, 0)->nullable(); // NOT NULL
            $table->double('Redeem_amount', 25, 2)->nullable(); // NOT NULL
            $table->double('Bonus_points', 25, 0)->nullable(); // NOT NULL
            $table->double('Loyalty_points', 25, 0)->nullable(); // NOT NULL
            $table->double('Balance_to_pay', 25, 2)->nullable(); // NOT NULL
            $table->unsignedBigInteger('Payment_type_id')->nullable();  ////  NOT NULL
            $table->string('Branch_code')->nullable();  ////  NOT NULL
            $table->string('Branch_type')->nullable();  ////   NULL
            $table->unsignedBigInteger('Bill_no')->nullable();  ////  NOT NULL
            $table->string('Source')->nullable();  ////   NULL
            $table->double('Account_no', 25, 0)->nullable(); // NOT NULL
            $table->unsignedBigInteger('Member2_enroll_id')->nullable();  ////  NOT NULL  lsl_enrollment_master
            $table->string('Membership2_id')->nullable();  ////   NOT NULL
            $table->double('Transfer_points', 25, 0)->nullable(); // NOT NULL
            $table->string('Benefit_description')->nullable();  ////   NULL
            $table->unsignedBigInteger('Sold_by')->nullable();  ////  NOT NULL
            $table->unsignedBigInteger('Member_pin')->nullable();  ////  NOT NULL
            $table->string('Delivery_flag')->nullable();  ////   NULL
            $table->unsignedBigInteger('Voucher_status')->nullable();  ////  NOT NULL
            $table->string('Transaction_status')->nullable();  ////   NULL
            $table->unsignedBigInteger('Order_status')->nullable();  ////  NOT NULL
            $table->unsignedBigInteger('Voucher_no')->nullable();  ////  NOT NULL
            $table->unsignedBigInteger('Order_no')->nullable();  ////  NOT NULL
            $table->unsignedBigInteger('Report_status')->nullable();  ////  NOT NULL
            $table->unsignedBigInteger('Create_user_id')->nullable();  ////  NOT NULL
            $table->dateTime('Create_date')->nullable();  ////   NULL
            $table->foreign('item_id')->references('item_id')->on('lsl_pos_inventory_master')->onDelete('cascade');
            //$table->foreign('Transaction_type_code')->references('Transaction_type_code')->on('lsl_transaction_type_master')->onDelete('cascade');
            //$table->foreign('Company_transaction_Type')->references('Company_transaction_Type')->on('lsl_company_transaction_type_master')->onDelete('cascade');
            //$table->foreign('Transaction_channel_code')->references('Transaction_channel_code')->on('lsl_transaction_channel_master')->onDelete('cascade');
            //$table->foreign('Company_id')->references('Company_id')->on('lsl_company_master')->onDelete('cascade');
            $table->foreign('Member1_enroll_id')->references('Enrollment_id')->on('lsl_enrollment_master')->onDelete('cascade');
            //$table->foreign('Member2_enroll_id')->references('Member2_enroll_id')->on('lsl_enrollment_master')->onDelete('cascade');
            //$table->foreign('Sold_by')->references('Sold_by')->on('lsl_enrollment_master')->onDelete('cascade');
            //$table->foreign('Update_user_id')->references('Update_user_id')->on('lsl_enrollment_master')->onDelete('cascade');
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
        Schema::dropIfExists('lsl_transaction');
    }
}

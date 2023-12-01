<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLslPointsAwardTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lsl_points_award_transaction', function (Blueprint $table) {
            $table->bigIncrements('transaction_id');
            //$table->unsignedBigInteger('transaction_type_id'); //->nullable();  ////  NOT NULL
            $table->unsignedBigInteger('membership_id');
            //$table->unsignedBigInteger('sold_by_id')->nullable();  ///// NOT NULL
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('payment_type_id');
            $table->unsignedBigInteger('gift_card_id')->nullable();
            $table->string('transaction_type')->default('Transaction With Loyalty');
            $table->string('purchase_gift_card')->default('No');
            //$table->unsignedBigInteger('item_id');
            $table->string('item')->default('Spend for loyalty: 0001');
            $table->unsignedBigInteger('quantity')->default(1);
            $table->double('price', 25, 2);
            $table->double('total_amount', 25, 2)->nullable();
            $table->double('total_vat_amount', 25, 2)->nullable();
            $table->double('balance_to_pay', 25, 2)->nullable();
            $table->double('redeem_point', 25, 0)->nullable();
            $table->double('redeem_amount', 25, 2)->nullable();
            $table->string('gift_card')->default('No');
            $table->double('total_balance_to_pay', 25, 2)->nullable();
            $table->unsignedBigInteger('branch_user_pin_id')->nullable();
            $table->string('remark')->unique()->nullable();
            //$table->foreign('transaction_type_id')->references('Transaction_id')->on('lsl_transaction_type_master')->onDelete('cascade');
            $table->foreign('membership_id')->references('Enrollment_id')->on('lsl_enrollment_master')->onDelete('cascade');
            //$table->foreign('sold_by_id')->references('Enrollment_id')->on('lsl_enrollment_master')->onDelete('cascade');
            $table->foreign('branch_id')->references('branch_id')->on('lsl_branch_master')->onDelete('cascade');
            $table->foreign('payment_type_id')->references('payment_id')->on('lsl_payment_type_master')->onDelete('cascade');
            $table->foreign('gift_card_id')->references('id')->on('lsl_gift_card_tbl')->onDelete('cascade');
            //$table->foreign('item_id')->references('item_id')->on('lsl_pos_inventory_master')->onDelete('cascade');
            $table->foreign('branch_user_pin_id')->references('Enrollment_id')->on('lsl_enrollment_master')->onDelete('cascade');
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
        Schema::dropIfExists('lsl_points_award_transaction');
    }
}

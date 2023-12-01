<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLslPosInventoryMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lsl_pos_inventory_master', function (Blueprint $table) {
            $table->bigIncrements('item_id');
            $table->string('Item_code')->nullable();  //// NOT NULL
            $table->string('Item_name')->nullable(); //// NULL
            $table->unsignedBigInteger('Company_id')->nullable();  ////  NOT NULL
            $table->unsignedBigInteger('Product_group_code')->nullable();  /// NOT NULL
            $table->unsignedBigInteger('Product_brand_code')->nullable();  ///  NOT NULL
            $table->unsignedBigInteger('Unit_of_transaction')->nullable(); //// NULL
            $table->unsignedBigInteger('Create_User_id')->nullable();  ///  NOT NULL
            $table->unsignedBigInteger('Update_User_id')->nullable();  ///  NOT NULL
            $table->double('Item_price', 25, 2)->nullable();  // NOT NULL
            $table->double('Threshold_balance', 25, 2)->nullable();  // NOT NULL
            $table->double('Current_balance', 25, 2)->nullable();  // NOT NULL
            $table->double('Item_vat', 5, 2)->nullable();  // NOT NULL
            $table->dateTime('Creation_date')->nullable();  ////  NOT NULL
            $table->dateTime('Update_date')->nullable();  ////  NOT NULL
            $table->foreign('Company_id')->references('Company_id')->on('lsl_company_master')->onDelete('cascade');
            $table->foreign('Product_group_code')->references('Product_group_id')->on('lsl_product_group_master')->onDelete('cascade');
            $table->foreign('Product_brand_code')->references('Product_brand_id')->on('lsl_product_brand_master')->onDelete('cascade');
            //$table->foreign('Unit_of_transaction')->references('Unit_of_transaction')->on('lsl_unit_of_item')->onDelete('cascade');
            $table->foreign('Create_User_id')->references('Enrollment_id')->on('lsl_enrollment_master')->onDelete('cascade');
            $table->timestamps();
            // lsl_company_transaction_channel_master
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lsl_pos_inventory_master');
    }
}

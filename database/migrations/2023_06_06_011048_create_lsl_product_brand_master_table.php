<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLslProductBrandMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lsl_product_brand_master', function (Blueprint $table) {
            $table->bigIncrements('Product_brand_id');
            $table->unsignedBigInteger('Company_id')->nullable();  ////  NOT NULL
            $table->unsignedBigInteger('Create_User_id')->nullable();  /// NOT NULL
            $table->unsignedBigInteger('Update_User_id')->nullable();  ///  NOT NULL
            $table->unsignedBigInteger('Product_group_code')->nullable();  ///  NOT NULL
            $table->string('Product_brand_code')->nullable(); //// NULL
            $table->dateTime('Creation_date')->nullable();  ////  NOT NULL
            $table->dateTime('Update_date')->nullable();  ////  NOT NULL
            $table->foreign('Company_id')->references('Company_id')->on('lsl_company_master')->onDelete('cascade');
            $table->foreign('Create_User_id')->references('Enrollment_id')->on('lsl_enrollment_master')->onDelete('cascade');
            $table->foreign('Product_group_code')->references('Product_group_id')->on('lsl_product_group_master')->onDelete('cascade');
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
        Schema::dropIfExists('lsl_product_brand_master');
    }
}

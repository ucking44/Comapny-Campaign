<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLslGiftCardTblTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lsl_gift_card_tbl', function (Blueprint $table) {
            $table->id();
            $table->string('Gift_card_number')->nullable();  //// NOT NULL
            $table->double('Gift_card_balance', 25, 0)->nullable();  // NOT NULL
            //$table->unsignedBigInteger('Company_id')->nullable();  ////  NOT NULL
            $table->unsignedBigInteger('Active_flag')->nullable();  ////  NOT NULL
            $table->double('Purchase_amt', 25, 2)->nullable();  // NOT NULL
            //$table->foreign('Company_id')->references('Company_id')->on('lsl_company_master')->onDelete('cascade');
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
        Schema::dropIfExists('lsl_gift_card_tbl');
    }
}

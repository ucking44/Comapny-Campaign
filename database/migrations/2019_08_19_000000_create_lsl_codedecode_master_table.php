<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLslCodedecodeMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lsl_codedecode_master', function (Blueprint $table) {
            $table->bigIncrements('Code_id');
            $table->unsignedBigInteger('Typecd_id')->nullable();
            $table->string('Decode_description')->nullable();
            $table->foreign('Typecd_id')->references('Typecd_id')->on('lsl_codedecode_type_master')->onDelete('cascade');
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
        Schema::dropIfExists('lsl_codedecode_master');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLslCompanyMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lsl_company_master', function (Blueprint $table) {
            $table->bigIncrements('Company_id');
            $table->string('Company_name')->nullable();
            $table->unsignedBigInteger('Company_type')->nullable();  // lsl_codedecode_master
            $table->unsignedBigInteger('Solution_type')->nullable();  //lsl_codedecode_master
            $table->string('Membership_generation')->nullable();
            $table->string('Membership_no_series')->nullable();
            $table->unsignedBigInteger('Country')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Region')->nullable();
            $table->unsignedBigInteger('Zone')->nullable();
            $table->unsignedBigInteger('State')->nullable();
            $table->unsignedBigInteger('City')->nullable();
            $table->text('Address')->nullable();
            $table->unsignedBigInteger('Pin_code')->nullable();
            $table->string('Company_person_name')->nullable();
            $table->string('Company_person_email')->nullable();
            $table->string('Company_person_phone_no')->nullable();
            $table->string('Company_secondary_person_name')->nullable();
            $table->string('CCompany_secondary_person_email')->nullable();
            $table->string('Company_secondary_person_phone_no')->nullable();
            $table->string('Website')->nullable();
            $table->string('Company_logo')->nullable();
            $table->string('Company_reg_no')->nullable();
            $table->dateTime('Comp_reg_date')->nullable();
            $table->double('Points_value_definition', 5, 2)->nullable();
            $table->unsignedBigInteger('Points_expiry_period')->nullable();
            $table->string('Allow_Customer_password_expiry')->nullable();
            $table->unsignedBigInteger('Customer_password_expiry_period')->nullable();
            $table->unsignedBigInteger('E_voucher_expiry_period')->nullable();
            $table->string('Pin_no_applicable')->nullable();
            $table->string('Corporate_email_id')->nullable();
            $table->double('VAT', 5, 2)->nullable();
            $table->double('LSL_markup', 5, 2)->nullable();
            $table->string('Referred_by')->nullable();
            $table->unsignedBigInteger('Member_expiry')->nullable();
            $table->string('E_voucher_llosource')->nullable();
            $table->unsignedBigInteger('Order_no_series')->nullable();
            $table->unsignedBigInteger('SMS_limit')->nullable();
            $table->unsignedBigInteger('Available_sms')->nullable();
            $table->double('Current_balance', 25, 0)->nullable();
            $table->string('Domain')->nullable();
            $table->unsignedBigInteger('Querylog_ticket')->nullable();
            $table->unsignedBigInteger('Create_User_id')->nullable();
            $table->dateTime('Creation_date')->nullable();
            $table->unsignedBigInteger('Update_User_id')->nullable();
            $table->dateTime('Update_date')->nullable();
            $table->string('Active_flag')->nullable();
            $table->foreign('Company_type')->references('Code_id')->on('lsl_codedecode_master')->onDelete('cascade');
            //$table->foreign('Solution_type')->references('Code_id')->on('lsl_codedecode_master')->onDelete('cascade');
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
        Schema::dropIfExists('lsl_company_master');
    }
}

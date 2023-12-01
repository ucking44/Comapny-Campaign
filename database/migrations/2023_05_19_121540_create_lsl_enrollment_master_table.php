<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLslEnrollmentMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lsl_enrollment_master', function (Blueprint $table) {
            $table->bigIncrements('Enrollment_id');
            $table->string('First_name')->nullable();
            $table->string('Middle_name')->nullable();
            $table->string('Last_name')->nullable();
            $table->longText('Address')->nullable();
            $table->unsignedBigInteger('Zipcode')->nullable();
            // $table->unsignedBigInteger('Country')->nullable(); // NOT NULL
            // $table->unsignedBigInteger('Region')->nullable();
            // $table->unsignedBigInteger('Zone')->nullable();
            // $table->unsignedBigInteger('State')->nullable();
            // $table->unsignedBigInteger('City')->nullable();
            $table->string('Phone_no')->nullable();
            $table->string('Sex')->nullable();
            $table->string('Profession')->nullable();
            $table->string('User_Email_id')->nullable();  // NOT NULL
            $table->string('Password')->nullable();  // NOT NULL
            $table->unsignedBigInteger('User_group_id')->nullable();  // NOT NULL
            $table->unsignedBigInteger('User_type_id')->nullable();  // NOT NULL
            $table->date('Birth_date')->nullable();
            $table->date('Anniversary_date')->nullable();
            $table->string('Photograph')->nullable();
            $table->string('Timezone')->nullable();
            $table->unsignedBigInteger('Referee_id')->nullable();
            $table->unsignedBigInteger('Company_id')->nullable();  // NOT NULL
            $table->string('Employee_flag')->nullable();
            $table->unsignedBigInteger('Employee_id')->nullable();
            $table->string('Membership_id')->unique()->nullable();  // NOT NULL
            $table->unsignedBigInteger('Pin')->nullable();  // NOT NULL
            $table->unsignedBigInteger('Partner_id')->nullable();  // NOT NULL
            $table->string('Branch_code')->nullable();  // NOT NULL
            $table->string('Referee_flag')->nullable();
            $table->string('Parent_member_id')->nullable();
            $table->double('balance', 25, 2)->nullable();  // NOT NULL
            $table->double('Total_purchase_amount', 25, 2)->nullable(); // NOT NULL
            $table->double('Total_redeem_points', 25, 0)->nullable();  // NOT NULL
            $table->double('Total_gained_points', 25, 0)->nullable();  // NOT NULL
            $table->double('Total_bonus_points', 25, 0)->nullable();  // NOT NULL
            $table->date('Membership_validity')->nullable();
            $table->unsignedBigInteger('Tier_id')->nullable();  // NOT NULL
            $table->date('Password_expiry_date')->nullable();
            $table->date('Enroll_date')->nullable();
            $table->unsignedBigInteger('Active_flag')->nullable();  // NOT NULL
            $table->string('Source')->nullable();
            $table->double('Account_number', 25, 0)->nullable();  // NOT NULL
            $table->string('Familly_flag')->nullable()->default('NO');
            //$table->string('Familly_flag')->nullable();
            $table->unsignedBigInteger('Family_redeem_limit')->nullable();  // NOT NULL
            $table->unsignedBigInteger('Loyalty_programme_id')->nullable();  // NOT NULL
            $table->unsignedBigInteger('Parent_enroll_id')->nullable();  // NOT NULL
            $table->double('Blocked_points', 25, 0)->nullable();  // NOT NULL
            $table->unsignedBigInteger('Communication_flag')->nullable();  // NOT NULL
            $table->string('Communication_flag_remarks')->nullable();
            $table->unsignedBigInteger('Create_user_id')->nullable();  // NOT NULL
            $table->dateTime('Create_date')->nullable();
            $table->unsignedBigInteger('Update_user_id')->nullable();  // NOT NULL
            $table->dateTime('Update_date')->nullable();
            $table->foreign('Loyalty_programme_id')->references('Loyalty_program_id')->on('lsl_loyalty_program_master')->onDelete('cascade');
            $table->foreign('Company_id')->references('Company_id')->on('lsl_company_master')->onDelete('cascade');
            // $table->foreign('Country')->references('Country_id')->on('lsl_country_currency_master')->onDelete('cascade');
            // $table->foreign('Region')->references('Region_id')->on('lsl_region_master')->onDelete('cascade');
            // $table->foreign('Zone')->references('Zone_id')->on('lsl_zone_master')->onDelete('cascade');
            // $table->foreign('State')->references('State_id')->on('lsl_state_master')->onDelete('cascade');
            // $table->foreign('City')->references('City_id')->on('lsl_city_master')->onDelete('cascade');
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
        Schema::dropIfExists('lsl_enrollment_master');
    }
}

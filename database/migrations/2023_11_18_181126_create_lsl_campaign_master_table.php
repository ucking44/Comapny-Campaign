<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLslCampaignMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lsl_campaign_master', function (Blueprint $table) {
            $table->bigIncrements('Campaign_id');
            $table->unsignedBigInteger('Company_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Create_User_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Update_User_id')->nullable();
            $table->unsignedBigInteger('Tier_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Product_group_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Product_brand_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Transaction_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Transaction_channel_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('branch_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Sweepstake_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Benefit_partner_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Loyalty_program_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Game_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Company_game_id')->nullable(); // NOT NULL
            $table->unsignedBigInteger('Game_configuration_id')->nullable(); // NOT NULL
            $table->string('Campaign_name')->nullable();  // NOT NULL
            $table->longText('Campaign_description')->nullable(); // NOT NULL
            $table->bigInteger('Campaign_type')->nullable(); // NOT NULL
            $table->bigInteger('Campaign_sub_type')->nullable();  // NOT NULL
            $table->date('From_date')->nullable();  // NOT NULL
            $table->date('To_date')->nullable();  // NOT NULL
            $table->bigInteger('Tier_flag')->default(0)->nullable();  // NOT NULL                            Changed on Sunday  default('No');
            $table->bigInteger('Active_flag')->default(1)->nullable();  // NOT NULL
            $table->bigInteger('Reward_flag')->default(0)->nullable();  // NOT NULL
            $table->double('Reward_points', 25, 0)->default(0)->nullable();  // NOT NULL
            $table->double('Reward_percent', 5, 2)->default(0)->nullable();  // NOT NULL
            $table->double('Cashback_percent', 5, 2)->default(0)->nullable();  // NOT NULL
            $table->bigInteger('Sweepstake_flag')->default(0)->nullable();  // NOT NULL                        Changed on Sunday  default('No');
            $table->bigInteger('Sweepstake_ticket_limit')->default(0)->nullable();  // NOT NULL
            $table->bigInteger('Reward_once_flag')->default(0)->nullable();  // NOT NULL
            $table->bigInteger('Transaction_amt_flag')->default(0)->nullable();  // NOT NULL                  Changed on Sunday  default('No');
            $table->double('Transaction_amount', 25, 2)->default(0)->nullable();  // NOT NULL
            $table->string('operator')->nullable();  // NOT NULL
            $table->bigInteger('Reward_fix_amt_flag')->default(0)->nullable();  // NOT NULL
            $table->double('Fixed_amount', 25, 2)->default(0)->nullable();  // NOT NULL
            $table->double('First_iteration_percentage', 5, 2)->default(0)->nullable();  // NOT NULL
            $table->double('Second_iteration_percentage', 5, 2)->default(0)->nullable();  // NOT NULL
            $table->bigInteger('Reward_fix_frequency_flag')->default(0)->nullable();  // NOT NULL
            $table->bigInteger('Fixed_frequency_count')->default(0)->nullable();  // NOT NULL
            $table->double('Max_reward_budget', 25, 2)->default(0)->nullable();  // NOT NULL
            $table->double('Max_reward_budget_cust', 25, 2)->default(0)->nullable();  // NOT NULL
            $table->double('Cumulative_amount', 25, 2)->default(0)->nullable();  // NOT NULL
            $table->bigInteger('Special_day')->default(0)->nullable();  // NOT NULL
            $table->bigInteger('Recuring_campaign_flag')->default(0)->nullable();  // NOT NULL
            $table->bigInteger('Recuring_campaign_flag')->default(0)->nullable();
            $table->bigInteger('Schedule')->default(0)->nullable();  // NOT NULL
            $table->date('Start_time')->nullable();  // NOT NULL
            $table->date('End_time')->nullable();  // NOT NULL
            $table->bigInteger('Spend_amt_flag')->default(0)->nullable();  // NOT NULL
            $table->double('Discount', 25, 2)->default(0)->nullable();  // NOT NULL
            $table->string('Discrete_amt')->nullable();   // NOT NULL   NEW
            $table->bigInteger('Special_occasian_criteria')->default(0)->nullable();  // NOT NULL  NEW
            $table->double('Spend_amount', 25, 2)->default(0)->nullable();   // NOT NULL   NEW
            $table->bigInteger('Partner_subcategory_id')->default(0)->nullable();  // NOT NULL  NEW
            $table->string('Upgrade_privilege')->nullable();  // NOT NULL  NEW
            $table->bigInteger('LBS_linked')->default(0)->nullable();  // NOT NULL  NEW
            $table->string('Benefit_description')->nullable();  // NOT NULL
            $table->string('Benefit_communication')->nullable();  // NOT NULL
            $table->string('campaign_status')->default('Disabled')->nullable();                          // Added on Sunday  default('Disabled');
            $table->dateTime('Create_date')->nullable();  // NOT NULL
            $table->dateTime('Update_date')->nullable();
            $table->foreign('Company_id')->references('Company_id')->on('lsl_company_master')->onDelete('cascade');
            $table->foreign('Create_User_id')->references('Enrollment_id')->on('lsl_enrollment_master')->onDelete('cascade');
            //$table->foreign('Code_id')->references('Code_id')->on('lsl_codedecode_master')->onDelete('cascade');
            $table->foreign('Tier_id')->references('Tier_id')->on('lsl_tier_master')->onDelete('cascade');
            $table->foreign('Product_group_id')->references('Product_group_id')->on('lsl_product_group_master')->onDelete('cascade');
            $table->foreign('Product_brand_id')->references('Product_brand_id')->on('lsl_product_brand_master')->onDelete('cascade');
            $table->foreign('Transaction_id')->references('Transaction_id')->on('lsl_transaction_type_master')->onDelete('cascade');
            $table->foreign('Transaction_channel_id')->references('Transaction_channel_id')->on('lsl_company_transaction_channel_master')->onDelete('cascade');
            $table->foreign('branch_id')->references('branch_id')->on('lsl_branch_master')->onDelete('cascade');
            $table->foreign('Sweepstake_id')->references('Sweepstake_id')->on('lsl_sweepstake_master')->onDelete('cascade');
            $table->foreign('Benefit_partner_id')->references('Partner_id')->on('lsl_partner_master')->onDelete('cascade');
            $table->foreign('Loyalty_program_id')->references('Loyalty_program_id')->on('lsl_loyalty_program_master')->onDelete('cascade');
            $table->foreign('Game_id')->references('Game_id')->on('lsl_game_master')->onDelete('cascade');
            $table->foreign('Company_game_id')->references('Company_game_id')->on('lsl_game_company_master')->onDelete('cascade');
            $table->foreign('Game_configuration_id')->references('Game_configuration_id')->on('lsl_game_company_configuration')->onDelete('cascade');
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
        Schema::dropIfExists('lsl_campaign_master');
    }
}

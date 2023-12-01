<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LslCampaignMaster extends Model
{
    use HasFactory;

    protected $table = "lsl_campaign_master";

    protected $primaryKey = 'Campaign_id';

    protected $fillable = [
        "Company_id",
        "Create_User_id",
        "Update_User_id",
        //"Code_id",
        "Tier_id",
        "Sweepstake_id",
        "Loyalty_program_id",
        "Game_id",
        "Game_configuration_id",
        "Campaign_name",
        "Campaign_description",
        "Campaign_type",
        "Campaign_sub_type",
        "From_date",
        "To_date",
        "Tier_flag",
        "Active_flag",
        "Reward_flag",
        "Reward_points",
        "Reward_percent",
        "Cashback_percent",
        "Sweepstake_flag",
        "Sweepstake_ticket_limit",
        "Reward_once_flag",
        "Transaction_amt_flag",
        "Transaction_amount",
        "operator",
        "Reward_fix_amt_flag",
        "Fixed_amount",
        "First_iteration_percentage",
        "Second_iteration_percentage",
        "Reward_fix_frequency_flag",
        "Fixed_frequency_count",
        "Max_reward_budget",
        "Max_reward_budget_cust",
        "Cumulative_amount",
        "Special_day",
        "Recuring_campaign_flag",
        "Shedule",
        "Start_time",
        "End_time",
        "Spend_amt_flag",
        "Discount",
        "Discrete_amt",
        "Special_occasian_criteria",
        "Spend_amount",
        "Partner_subcategory_id",
        "Upgrade_privilege",
        "LBS_linked",

        "Benefit_description",
        "Benefit_communication",
        "Create_date",
        "Update_date"
    ];

}

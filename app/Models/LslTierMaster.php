<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LslTierMaster extends Model
{
    use HasFactory;

    protected $table = "lsl_tier_master";

    protected $primaryKey = 'Tier_id';

    protected $fillable = [
        "Company_id",
        "Create_User_id",
        "Update_User_id",
        "Tier_level_id",
        "Upgrade_criteria",
        "Retention_criteria",
        "Tier_name",
        "Tier_period",
        "Upgrade_criteria_value",
        "Redeemption_level",
        "minimum_points_balance",
        "minimum_points_balance_redeem",
        "Tier_invitation",
        "Creation_date",
        "Update_date"
    ];
    
}

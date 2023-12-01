<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LslTierCriteriaMaster extends Model
{
    use HasFactory;

    protected $table = "lsl_tier_criteria_master";

    protected $primaryKey = 'Tier_criteria_id';

    protected $fillable = [
        "Tier_criteria_name",
        "Criteria_description",
        "Create_User_id",
        "Update_User_id",
        "Create_date",
        "Update_date"
    ];
    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LslTierLevelMaster extends Model
{
    use HasFactory;

    protected $table = "lsl_tier_level_master";

    protected $primaryKey = 'Tier_level_id';

    protected $fillable = [
        "Tier_level",
        "Create_User_id",
        "Update_User_id",
        "Create_date",
        "Update_date"
    ];
    
}

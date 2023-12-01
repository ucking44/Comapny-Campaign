<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LslGameCampaignChild extends Model
{
    use HasFactory;

    protected $table = "lsl_game_campaign_child";

    protected $primaryKey = 'Child_id';

    protected $fillable = [
        "Company_id",
        "Campaign_id",
        "Game_id",
        "Company_game_id",
        "Game_level",
        "Reward_points"
    ];

}

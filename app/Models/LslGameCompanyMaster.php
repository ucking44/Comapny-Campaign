<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LslGameCompanyMaster extends Model
{
    use HasFactory;

    protected $table = "lsl_game_company_master";

    protected $primaryKey = 'Company_game_id';

    protected $fillable = [
        "Company_id",
        "Create_User_id",
        "Update_User_id",
        "Game_id",
        "Link_to_game_campaign",
        "Game_for_fun",
        "Game_for_competition",
        "Total_game_winner",
        "Competition_winner_award",
        "Lives_flag",
        "Initial_game_lives",
        "Points_value_for_one",
        "Active_flag",
        "Competition_start_date",
        "Competition_end_date",
        "Create_date",
        "Update_date",
    ];

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LslGameLevelWinner extends Model
{
    use HasFactory;

    protected $table = "lsl_game_level_winner";

    protected $primaryKey = 'Winner_id';

    protected $fillable = [
        "Company_id",
        "Enrollment_id",
        "Campaign_id",
        "Game_id",
        "Company_game_id",
        "Game_level",
        "Fun_game_iteration",
        "Campaign_game_iteration",
        "Competition_game_iteration",
        "Game_type",
        "Gained_points",
        "Game_score",
        "Game_winner_flag"
    ];

}

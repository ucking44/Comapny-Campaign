<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LslGameMaster extends Model
{
    use HasFactory;

    protected $table = "lsl_game_master";

    protected $primaryKey = 'Game_id';

    protected $fillable = [
        "Create_User_id",
        "Update_User_id",
        "Game_name",
        "Description",
        "Game_image_flag",
        "Time_based_flag",
        "Moves_based_flag",
        "Score_based_flag",
        "Create_date",
        "Update_date",
    ];

}

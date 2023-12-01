<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LslGameApproveTemp extends Model
{
    use HasFactory;

    protected $table = "lsl_game_approve_temp";

    protected $primaryKey = 'Temp_id';

    protected $fillable = [
        "Company_id",
        "Enrollment_id",
        "Game_id",
        "Company_game_id",
        "Winner_Name",
        "Winner_Level",
        "Points_Prize",
        "Game_Name",
        "Highest_Score",
        "Award_flag",
        "Current_balance",
        "flag"
    ];

}

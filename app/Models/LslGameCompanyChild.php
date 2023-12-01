<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LslGameCompanyChild extends Model
{
    use HasFactory;

    protected $table = "lsl_game_company_child";

    protected $primaryKey = 'Company_game_child_id';

    protected $fillable = [
        "Company_id",
        "Game_id",
        "Company_game_id",
        "Competition_winner_leve",
        "Game_points_prize",
        "Game_prize_image"
    ];

}

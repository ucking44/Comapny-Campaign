<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LslGameCompanyConfiguration extends Model
{
    use HasFactory;

    protected $table = "lsl_game_company_configuration";

    protected $primaryKey = 'Game_configuration_id';

    protected $fillable = [
        "Company_id",
        "Create_User_id",
        "Update_User_id",
        "Game_id",
        "Company_game_id",
        "Game_level",
        "Total_moves",
        "Issued_lives",
        "Game_matrix",
        "Game_image",
        "Time_for_level",
        "Create_date",
        "Update_date",
    ];

}

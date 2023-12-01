<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LslGameMemberLive extends Model
{
    use HasFactory;

    protected $table = "lsl_game_member_lives";

    protected $primaryKey = 'Child_id';

    protected $fillable = [
        "Company_id",
        "Enrollment_id",
        "Game_id",
        "Company_game_id",
        "Enrollment_id2",
        "Game_level",
        "Lives_for_level",
        "Transfer_lives"
    ];

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LslSweepstakeMaster extends Model
{
    use HasFactory;

    protected $table = "lsl_sweepstake_master";

    protected $primaryKey = "Sweepstake_id";

    protected $fillable = [
        "Company_id",
        "Create_User_id",
        "Update_user_id",
        "Sweepstake_name",
        "Winners",
        "Prize",
        "Prize_description",
        "Prize_image",
        "Link_to_campaign",
        "From_date",
        "To_date",
        "Active_flag",
        "Create_date",
        "Update_date",
    ];

}

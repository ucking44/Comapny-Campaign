<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LslPromoCampaign extends Model
{
    use HasFactory;

    protected $table = "lsl_promo_campaign";

    protected $primaryKey = "Campaign_id";

    protected $fillable = [
        "Company_id",
        "Loyalty_program_id",
        "Sweepstake_id",
        "Sweepstake_flag",
        "Sweepstake_ticket_limit",
        "Date",
        "Points",
        "File_name",
        "Promo_code",
        "Campaign_description",
        "Promo_code_status",
        "Active_flag",
        "campaign_status",
        "Start_date",
        "End_date"
    ];

}

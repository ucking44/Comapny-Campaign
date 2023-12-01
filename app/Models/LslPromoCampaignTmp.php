<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LslPromoCampaignTmp extends Model
{
    use HasFactory;

    protected $table = "lsl_promo_campaign_tmp";

    protected $primaryKey = "Campaign_id";

    protected $fillable = [
        "Company_id",
        "Points",
        "File_name",
        "Promo_code",
        "Start_date",
        "End_date"
    ];

}

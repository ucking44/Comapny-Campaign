<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LslCampaignValidity extends Model
{
    use HasFactory;

    protected $table = "lsl_campaign_validity";

    protected $primaryKey = 'id';

    protected $fillable = [
        "Company_id",
        "Campaign_id",
        "From_date",
        "To_date",
        "Recuring_count"
    ];

}

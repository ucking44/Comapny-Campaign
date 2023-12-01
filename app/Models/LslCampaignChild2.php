<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LslCampaignChild2 extends Model
{
    use HasFactory;

    protected $table = "lsl_campaign_child2";

    protected $primaryKey = 'id';

    protected $fillable = [
        "Company_id",
        "Campaign_id",
        "Campaign_name",
        "Transaction_id",
        "Transaction_channel_id",
        "Campaign_type",
        "Campaign_sub_type",
    ];

}

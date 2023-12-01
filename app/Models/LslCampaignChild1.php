<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LslCampaignChild1 extends Model
{
    use HasFactory;

    protected $table = "lsl_campaign_child1";

    protected $primaryKey = 'id';

    protected $fillable = [
        "Company_id",
        "Campaign_id",
        "Campaign_name",
        "Product_group_code",
        "Product_brand_code",
        "Item_code",
        "Campaign_type",
        "Campaign_sub_type",
    ];

}

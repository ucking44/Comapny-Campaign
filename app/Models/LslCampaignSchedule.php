<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LslCampaignSchedule extends Model
{
    use HasFactory;

    protected $table = "lsl_campaign_schedule";

    protected $primaryKey = 'id';

    protected $fillable = [
        "Campaign_id",
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec",
        "Mon",
        "Tue",
        "Wed",
        "Thu",
        "Fri",
        "Sat",
        "Sun",
        "First_week",
        "Second_week",
        "Third_week",
        "Fourth_week",

    ];

}

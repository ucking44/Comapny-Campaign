<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lslBranchMaster extends Model
{
    use HasFactory;

    protected $table = "lsl_branch_master";

    protected $primaryKey = 'branch_id';

    protected $fillable = [
        "Company_id",
        "Partner_id",
        "Create_User_id",
        "Update_User_id",
        "branch_code",
        "branch_name",
        "branch_address",
        "country_currency_id",
        "region_id",
        "zone_id",
        "state_id",
        "city_id",
        "Pos_series_no",
        "Receipt_series_no",
        "Total_membership_cards",
        "Creation_date",
        "Update_date"
    ];

}

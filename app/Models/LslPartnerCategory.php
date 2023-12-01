<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LslPartnerCategory extends Model
{
    use HasFactory;

    protected $table = "lsl_partner_category";

    protected $primaryKey = 'Partner_category_id';

    // public function lslEnrollmentMasters()
    // {
    //     return $this->hasMany(LslEnrollmentMaster::class);
    // }

    protected $fillable = [
        "Create_User_id",
        "Update_User_id",
        "Partner_category_name",
        "Active_flag",
        "Creation_date",
        "Update_date",
    ];

}

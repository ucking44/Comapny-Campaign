<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LslPartnerMaster extends Model
{
    use HasFactory;

    protected $table = "lsl_partner_master";

    protected $primaryKey = 'Partner_id';

    // public function lslEnrollmentMasters()
    // {
    //     return $this->hasMany(LslEnrollmentMaster::class);
    // }

    protected $fillable = [
        "Company_id",
        "Create_User_id",
        "Update_User_id",
        "Partner_category_id",
        "Partner_type",
        "Partner_name",
        "Partner_address",
        "Partner_contact_person_name",
        "Partner_contact_person_email",
        "Partner_logo",
        "Corporate_email",
        "Create_date",
        "Update_date",
    ];

}

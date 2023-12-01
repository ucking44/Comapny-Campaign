<?php

namespace App\Models;

use App\Models\LslEnrollmentMaster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LslLoyaltyProgramMaster extends Model
{
    use HasFactory;

    protected $table = "lsl_loyalty_program_master";

    protected $primaryKey = 'Loyalty_program_id';

    public function lslEnrollmentMasters()
    {
        return $this->hasMany(LslEnrollmentMaster::class);
    }

    protected $fillable = [
        "Company_id",
        "Loyalty_program_name",
        "Loyalty_program_logo",
        "User_apply_to",
        "Create_User_id",
        "Update_User_id",
        "Creation_date",
        "Update_date",
        "Active_flag"
    ];

}

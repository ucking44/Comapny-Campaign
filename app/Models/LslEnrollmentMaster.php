<?php

namespace App\Models;

use App\Models\LslLoyaltyProgramMaster;
use Illuminate\Database\Eloquent\Model;
use App\Models\lslPointsAwardTransaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LslEnrollmentMaster extends Model
{
    use HasFactory;

    protected $table = "lsl_enrollment_master";

    protected $primaryKey = 'Enrollment_id';

    public function lslLoyaltyProgramMasters()
    {
        return $this->belongsTo(LslLoyaltyProgramMaster::class);
    }

    public function lslPointAwardTransaction()
    {
        return $this->hasMany(lslPointsAwardTransaction::class);
    }

    protected $fillable = [
        "First_name",
        "Middle_name",
        "Last_name",
        "Address",
        "Zipcode",
        "Country",
        "Region",
        "Zone",
        "State",
        "City",
        "Phone_no",
        "Sex",
        "Profession",
        "User_Email_id",
        "Password",
        "User_group_id",
        "User_type_id",
        "Birth_date",
        "Anniversary_date",
        "Photograph",
        "Timezone",
        "Referee_id",
        "Company_id",
        "Employee_flag",
        "Employee_id",
        "Membership_id",
        "Pin",
        "Partner_id",
        "Branch_code",
        "Referee_flag",
        "Parent_member_id",
        "Current_balance",
        "Total_purchase_amount",
        "Total_redeem_points",
        "Total_gained_points",
        "Total_bonus_points",
        "Membership_validity",
        "Tier_id",
        "Password_expiry_date",
        "Enroll_date",
        "Active_flag",
        "Source",
        "Account_number",
        "Familly_flag",
        "Family_redeem_limit",
        "Loyalty_programme_id",
        "Parent_enroll_id",
        "Blocked_points",
        "Communication_flag",
        "Communication_flag_remarks",
        "Create_user_id",
        "Create_date",
        "Update_user_id",
        "Update_date"
    ];

}

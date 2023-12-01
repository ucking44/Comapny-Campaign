<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LslCashbackTransaction extends Model
{
    use HasFactory;

    protected $table = "lsl_cashback_transaction";

    protected $primaryKey = 'id';

    protected $fillable = [
        "Company_id",
        "Enrollment_id",
        "Branch_id",
        "Campaign_id",
        "Campaign_name",
        "Customer_name",
        "Membership_id",
        "Account_no",
        "Transaction_amount",
        "Cashback_amount",
    ];

}

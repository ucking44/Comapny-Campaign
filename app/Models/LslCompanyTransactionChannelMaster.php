<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LslCompanyTransactionChannelMaster extends Model
{
    use HasFactory;

    protected $table = "lsl_company_transaction_channel_master";

    protected $primaryKey = "Transaction_channel_id";

    protected $fillable = [
        "Company_id",
        "Create_User_id",
        "Update_user_id",
        "Transaction_channel_code",
        "Transaction_channel_name",
        "Create_date",
        "Update_date",
    ];

}

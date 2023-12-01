<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LslTransactionTypeMaster extends Model
{
    use HasFactory;

    protected $table = "lsl_transaction_type_master";

    protected $primaryKey = 'Transaction_id';

    protected $fillable = [
        "Create_User_id",
        "Update_User_id",
        "Transaction_type_name",
        "Create_date",
        "Update_date"
    ];

}

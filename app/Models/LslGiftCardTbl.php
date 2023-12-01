<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LslGiftCardTbl extends Model
{
    use HasFactory;

    protected $table = "lsl_gift_card_tbl";

    //protected $primaryKey = 'id';

    protected $fillable = [
        "Gift_card_number",
        "Gift_card_balance",
        //"Company_id",
        "Active_flag",
        "Purchase_amt",
    ];

}

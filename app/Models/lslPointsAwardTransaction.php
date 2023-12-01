<?php

namespace App\Models;

use App\Models\LslEnrollmentMaster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class lslPointsAwardTransaction extends Model
{
    use HasFactory;

    protected $table = "lsl_points_award_transaction";

    protected $primaryKey = 'transaction_id';

    protected $fillable = [
        "transaction_type_id",
        "membership_id",
        "sold_by_id",
        "branch_id",
        "payment_type_id",
        "gift_card_id",
        "purchase_gift_card",
        "item_id",
        "item",
        "quantity",
        "price",
        "total_amount",
        "total_vat_amount",
        "balance_to_pay",
        "redeem_point",
        "redeem_amount",
        "gift_card",
        "total_balance_to_pay",
        "branch_user_pin_id",
        "remark"
    ];

    public function lslEnrollmentMasters()
    {
        return $this->belongsTo(LslEnrollmentMaster::class);
    }

}

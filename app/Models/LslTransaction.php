<?php

namespace App\Models;

use App\Models\LslPosInventoryMaster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LslTransaction extends Model
{
    use HasFactory;

    protected $table = "lsl_transaction";

    protected $primaryKey = 'Transaction_id';

    protected $fillable = [
        "Transaction_type_code",
        "Company_transaction_Type",
        "Transaction_channel_code",
        "Company_id",
        "Member1_enroll_id",
        "Membership1_id",
        "Branch_user_id",
        "Branch_user_emailid",
        "Transaction_date",
        "Gift_card",
        "Branch_user_pin",
        "Product_group_code",
        "Product_brand_code",
        "Item_code",
        "item_id",
        "Quantity",
        "VAT",
        "Transaction_amount",
        "Product_cost",
        "Redeem_points",
        "Redeem_amount",
        "Bonus_points",
        "Loyalty_points",
        "Balance_to_pay",
        "Payment_type_id",
        "Branch_code",
        "Branch_type",
        "Bill_no",
        "Source",
        "Account_no",
        "Member2_enroll_id",
        "Membership2_id",
        "Transfer_points",
        "Benefit_description",
        "Sold_by",
        "Member_pin",
        "Delivery_flag",
        "Voucher_status",
        "Transaction_status",
        "Order_status",
        "Voucher_no",
        "Order_no",
        "Report_status",
        "Create_user_id",
        "Create_date"
    ];

    public function lslPosInventoryMaster()
    {
        return $this->belongsTo(LslPosInventoryMaster::class);
    }

}

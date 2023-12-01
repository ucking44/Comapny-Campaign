<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LslPosInventoryMaster extends Model
{
    use HasFactory;

    protected $table = "lsl_pos_inventory_master";

    protected $primaryKey = 'item_id';

    protected $fillable = [
        "Item_code",
        "Item_name",
        "Company_id",
        "Product_group_code",
        "Product_brand_code",
        "Unit_of_transaction",
        "Item_price",
        "Threshold_balance",
        "Current_balance",
        "Item_vat",
        "Create_User_id",
        "Creation_date",
        "Update_User_id",
        "Update_date"
    ];

}

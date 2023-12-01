<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LslProductBrandMaster extends Model
{
    use HasFactory;

    protected $table = "lsl_product_brand_master";

    protected $primaryKey = "Product_brand_id";

    protected $fillable = [
        "Company_id",
        "Create_User_id",
        "Update_user_id",
        "Product_group_code",
        "Product_brand_code",
        "Creation_date",
        "Update_date",
    ];

}

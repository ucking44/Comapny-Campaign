<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LslProductGroupMaster extends Model
{
    use HasFactory;

    protected $table = "lsl_product_group_master";

    protected $primaryKey = 'Product_group_id';

    protected $fillable = [
        "Company_id",
        "Create_User_id",
        "Update_user_id",
        "Product_group_code",
        "Product_group_name",
        "Creation_date",
        "Update_date",
    ];

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LslPromoInvalidData extends Model
{
    use HasFactory;

    protected $table = "lsl_promo_invalid_data";

    protected $primaryKey = "temp_id";

    protected $fillable = [
        "Company_id",
        "Enrollment_id",
        "Create_date"
    ];

}

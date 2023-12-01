<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PromoCode;

class Program extends Model
{
    use HasFactory;

    protected $table = "programs";

    protected $primaryKey = "prog_id";

    protected $fillable = [
        "program_name",
        "slug",
        "status"
    ];

    public function promoCode()
    {
        return $this->hasMany(PromoCode::class);
    }

}

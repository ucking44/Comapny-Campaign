<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenerateToken extends Model
{
    use HasFactory;

    protected $table = "generate_tokens";

    protected $primaryKey = "id";

    protected $fillable = [
        "app_name",
        "token",
    ];

}

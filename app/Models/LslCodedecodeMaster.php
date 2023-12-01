<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LslCodedecodeMaster extends Model
{
    use HasFactory;

    protected $table = "lsl_codedecode_master";

    protected $primaryKey = 'Code_id';

    protected $fillable = [
        "Typecd_id",
        "Decode_description"
    ];

}

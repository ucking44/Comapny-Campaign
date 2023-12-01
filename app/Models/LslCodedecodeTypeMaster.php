<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LslCodedecodeTypeMaster extends Model
{
    use HasFactory;

    protected $table = "lsl_codedecode_type_master";

    protected $primaryKey = 'Typecd_id';

    protected $fillable = [
        "Typecd_description"
    ];

}

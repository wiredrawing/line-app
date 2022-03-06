<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineCallbackUrl extends Model
{
    use HasFactory;



    public $fillable = [
        "line_account_id",
        "url",
    ];
}

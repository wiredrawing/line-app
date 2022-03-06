<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineMessage extends Model
{
    use HasFactory;


    public $fillable = [
        "line_account_id",
        "type",
        "text",
        "delivery_datetime",
    ];
}

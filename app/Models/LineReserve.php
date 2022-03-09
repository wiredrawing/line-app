<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineReserve extends Model
{
    use HasFactory;



    public $fillable = [
        "line_account_id",
        "delivery_datetime",
        "is_sent",
    ];


    public function line_messages()
    {
        return $this->hasMany(LineMessage::class, "line_reserve_id", "id");
    }
}

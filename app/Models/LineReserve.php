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

    public $dates = [
        "delivery_datetime",
    ];

    public function line_messages()
    {
        return $this->hasMany(LineMessage::class, "line_reserve_id", "id");
    }

    public function line_broadcasts()
    {
        return $this->hasMany(LineBroadcast::class, "line_reserve_id", "id");
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineBroadcast extends Model
{
    use HasFactory;


    public $fillable = [
        "line_reserve_id",
        "line_member_id",
        "delivered_at",
    ];


    public function reserve()
    {
        return $this->hasOne(LineReserve::class, "id", "line_reserve_id");
    }


    public function member()
    {
        return $this->hasOne(LineMember::class, "id", "line_member_id");
    }
}

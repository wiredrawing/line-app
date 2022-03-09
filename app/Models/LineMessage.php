<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineMessage extends Model
{
    use HasFactory;


    public $fillable = [
        "line_reserve_id",
        "type",
        "text",
    ];



    /**
     * 予約のメッセージが紐づくline_reservesテーブルのレコード
     *
     * @return void
     */
    public function line_reserve()
    {
        return $this->belongsTo(LineReserve::class, "id", "line_reserve_id");
    }
}

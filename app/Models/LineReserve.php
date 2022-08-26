<?php

namespace App\Models;

use Database\Seeders\LineAccountSeeder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    /**
     * @return BelongsTo
     */
    public function line_account(): BelongsTo
    {
        return $this->belongsTo(LineAccount::class,"line_account_id");
    }
}

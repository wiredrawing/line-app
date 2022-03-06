<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineAccount extends Model
{
    use HasFactory;



    public $fillable = [
        "channel_id",
        "channel_secret",
        "user_id",
        "messaging_channel_id",
        "messaging_channel_secret",
        "messaging_user_id",
        "messaging_channel_access_token",
        "webhook_url",
        "is_enabled",
        "is_hidden",
    ];

    public function line_callback_urls()
    {
        return $this->hasMany(LineCallbackUrl::class, "line_account_id", "id");
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static findOrFail(mixed $line_account_id)
 */
class LineAccount extends Model
{
    use HasFactory;



    public $fillable = [
        "channel_name",
        "channel_id",
        "channel_secret",
        "user_id",
        "messaging_channel_id",
        "messaging_channel_secret",
        "messaging_user_id",
        "messaging_channel_access_token",
        "api_token",
        "webhook_url",
        "application_key",
        "is_displayed",
        "is_deleted",
    ];

    /**
     * @return HasMany
     */
    public function line_callback_urls(): HasMany
    {
        return $this->hasMany(LineCallbackUrl::class, "line_account_id", "id");
    }

    /**
     * @return HasMany
     */
    public function line_members(): HasMany
    {
        return $this->hasMany(LineMember::class, "line_account_id", "id");
    }
}

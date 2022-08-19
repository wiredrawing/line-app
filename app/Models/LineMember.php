<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LineMember extends Model
{
    use HasFactory;



    public $fillable = [
        "access_token",
        "expires_in",
        "id_token",
        "refresh_token",
        "scope",
        "token_type",
        "email",
        "picture",
        "name",
        "sub",
        "aud",
        "line_account_id",
        "api_token",
    ];

    /**
     * @return BelongsTo
     */
    public function line_account(): BelongsTo
    {
        return $this->belongsTo(LineAccount::class, "line_account_id", "id");
    }

    /**
     * @return HasOne
     */
    public function player(): HasOne
    {
        return $this->hasOne(Player::class, "line_member_id", "id");
    }

}

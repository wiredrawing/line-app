<?php

namespace App\Models;

use App\Notifications\LineMemberMessageNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property $id
 * @property $email
 * @method static create(array $new_line_info)
 * @method static findOrFail($id)
 * @method static where(array $array)
 */
class LineMember extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


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
        "password",
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


    public function sendEmailVerificationNotification()
    {
        // Lineログインした旨を送信するNotificationクラスを実行
        // 当該クラスは toMail()メソッドが実装されている必要がある
        $this->notify(new LineMemberMessageNotification());
    }
}

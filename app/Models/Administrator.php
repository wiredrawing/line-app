<?php

namespace App\Models;

use App\Notifications\AdministratorNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// ---------------------------------------------------------------
// 認証処理を任意のテーブルで利用する場合,以下のクラスを継承する
// ---------------------------------------------------------------
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;


class Administrator extends User
{
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * パスワードリセット通知の送信
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        // 自身のオブジェクト自体を渡しても良いと思う
        $administrator = $this;

        var_dump("start", __FUNCTION__);
        var_dump($token);
        $this->notify(new AdministratorNotification($administrator, $token));
        var_dump("start", __FUNCTION__);
    }
}

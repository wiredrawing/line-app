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


    public $fillable = [
        "name",
        "password",
        "email",
    ];


    protected $casts = [
        "email_verified_at" => "datetime",
    ];


    /**
     * パスワードリセット通知の送信
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        var_dump("start", __FUNCTION__);
        var_dump($token);
        $this->notify(new AdministratorNotification($token));
        var_dump("start", __FUNCTION__);
    }
}

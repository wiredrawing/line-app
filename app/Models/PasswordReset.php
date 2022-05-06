<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PasswordReset extends Model
{
    use HasFactory;


    /**
     * @return BelongsTo
     */
    public function administrator(): BelongsTo
    {
        // $this->belongsTo(モデルクラス名, "モデルクラス名の中のカラム名", "自身のテーブルのカラム名(オーナーcolumn)");
        return $this->belongsTo(Administrator::class, "email", "email");
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function line_account()
    {
        return $this->belongsTo(LineAccount::class, "line_account_id", "id");
    }
}

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
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

}

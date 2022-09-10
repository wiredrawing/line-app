<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameTitle extends Model
{
    use HasFactory;


    public $fillable = [
        "title",
        "platform_id",
        "description",
        "genre_id",
        "is_displayed",
        "is_deleted",
        "created_by",
        "updated_by",
    ];

}

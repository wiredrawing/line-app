<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Player extends Model
{
    use HasFactory;


    public $fillable = [
        "line_member_id",
        "family_name",
        "middle_name",
        "given_name",
        "nickname",
        "email",
        "is_displayed",
        "is_deleted",
        "gender_id",
        "is_published",
        "api_token",
    ];


    /**
     * @return HasOne
     */
    public function line_member(): HasOne
    {
        return $this->hasOne(LineMember::class, "id", "line_member_id");
    }
}

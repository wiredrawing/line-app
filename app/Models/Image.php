<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

// use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    public $primaryKey = "id";
    public $keyType = "string";
    public $incrementing = false;
    public $fillable = [
        "filename",
        "extension",
        "uploaded_at",
    ];

    public $appends = [
        "show_url",
    ];

    public $dates = [
        "uploaded_at",
    ];

    /**
     * 画像表示用 URL
     * @return string|null
     */
    public function getShowUrlAttribute(): ?string
    {
        if (isset($this->id)) {
            return route("front.api.top.image.show", [
                "id" => $this->id,
            ]);
        }
        return null;
    }
}

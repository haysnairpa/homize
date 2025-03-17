<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    public $table = "content";

    protected $dates = [
        "created_at",
        "deleted_at",
        "updated_at",
    ];

    protected $fillable = [
        "id_room",
        "id_sender",
        "content",
        "media_url",
    ];

    // many to one from content to room
    public function room()
    {
        return $this->belongsTo("App\Models\Room", "id_room", "id");
    }
}

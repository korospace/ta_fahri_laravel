<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mutual extends Model
{
    protected $table      = "mutual";
    protected $primaryKey = "id";
    protected $fillable   = [
        "username",
        "posts",
        "followers",
        "following",
        "bio",
        "is_verified",
        "is_private",
        "is_scraped",
        "is_followers_scraped",
    ];
}

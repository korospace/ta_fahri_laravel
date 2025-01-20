<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MutualFollowers extends Model
{
    protected $table      = "mutual_followers";
    protected $primaryKey = "id";
    protected $fillable   = [
        "mutual_id",
        "username",
    ];
}

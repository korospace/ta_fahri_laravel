<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Followers extends Model
{
    protected $table      = "followers";
    protected $primaryKey = "id";
    protected $fillable   = [
        "username",
        "href",
        "timestamp",
    ];
}

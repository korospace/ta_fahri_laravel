<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressUpload extends Model
{
    protected $table      = "progress_upload";
    protected $primaryKey = "id";
    protected $fillable   = [
        "progress_status",
        "tab_active",
        "tab_upload_data",
        "tab_mutual",
        "tab_mutual_detail",
        "tab_mutual_followers",
        "tab_node_graph",
    ];
}

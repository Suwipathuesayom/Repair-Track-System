<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairAttachment extends Model
{
    protected $fillable = [
        'repair_request_id',
        'file_path',
        'file_type',
        'created_at'
    ];

    public $timestamps = false;
}

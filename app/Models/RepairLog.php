<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairLog extends Model
{
    public $timestamps = false; 

    protected $casts = [
    'created_at' => 'datetime',
];

    protected $fillable = [
        'repair_request_id',
        'changed_by_user_id',
        'from_status',
        'to_status',
        'note',
        'created_at',
    ];

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by_user_id');
    }

    public function repairRequest()
    {
        return $this->belongsTo(RepairRequest::class);
    }

    
}

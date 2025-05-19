<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairRequest extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function attachments()
    {
        return $this->hasMany(RepairAttachment::class);
    }
    public function actions()
    {
        return $this->hasMany(RepairAction::class);
    }
    public function partsRequests()
    {
        return $this->hasMany(PartsRequest::class);
    }

    public function attachment()
    {
        return $this->hasOne(RepairAttachment::class);
    }

    public function logs()
    {
        return $this->hasMany(RepairLog::class);
    }


}

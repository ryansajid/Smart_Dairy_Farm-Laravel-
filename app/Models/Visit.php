<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    //
    protected $table = 'visits';
     protected $fillable = [
        'visitor_id',
        'meeting_user_id',
        'visit_type_id',
        'purpose',
        'schedule_time',
        'status',
        'approved_at',
        'rejected_reason'
    ];

    protected $casts = [
        'schedule_time' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

    public function meetingUser()
    {
        return $this->belongsTo(User::class, 'meeting_user_id');
    }

    public function type()
    {
        return $this->belongsTo(VisitType::class, 'visit_type_id');
    }

}

<?php

namespace App\Models;

class Visit extends Model
{
    protected $fillable = [
        'user_id',
        'customer_id',
        'visit_date',
        'visit_time',
        'purpose',
        'status',
        'notes',
        'next_followup_date',
        'location',
    ];

    const Status_Planned = 'planned';
    const Status_Done = 'done';
    const Status_Cancelled = 'cancelled';

    const Statuses = [
        self::Status_Planned => 'Planned',
        self::Status_Done => 'Done',
        self::Status_Cancelled => 'Cancelled',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}

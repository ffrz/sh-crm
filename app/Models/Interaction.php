<?php

namespace App\Models;

class Interaction extends Model
{
    protected $fillable = [
        'user_id',
        'customer_id',
        'service_id',
        'date',
        'type',
        'status',
        'subject',
        'summary',
        'notes',
    ];

    const Status_Planned = 'planned';
    const Status_Done = 'done';
    const Status_Cancelled = 'cancelled';

    const Statuses = [
        self::Status_Planned => 'Planned',
        self::Status_Done => 'Done',
        self::Status_Cancelled => 'Cancelled',
    ];

    const Type_Visit = 'visit';
    const Type_Call = 'call';
    const Type_Chat = 'chat';
    const Type_Email = 'email';
    const Type_Other = 'other';

    const Types = [
        self::Type_Visit => 'Visit',
        self::Type_Call => 'Call',
        self::Type_Chat => 'Chat',
        self::Type_Email => 'Email',
        self::Type_Other => 'Other',
    ];

    const EngagementLevel_None = 'none';
    const EngagementLevel_Cold = 'cold';
    const EngagementLevel_Warm = 'warm';
    const EngagementLevel_Hot = 'hot';
    const EngagementLevel_Customer = 'customer';
    const EngagementLevel_Churned = 'churned';
    const EngagementLevel_Lost = 'lost';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}

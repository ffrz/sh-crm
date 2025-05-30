<?php

namespace App\Models;


class CustomerService extends Model
{
    protected $fillable = [
        'customer_id',
        'service_id',
        'status',
        'start_date',
        'end_date',
        'notes'
    ];

    public const Status_Active = 'active';
    public const Status_Churned = 'churned';
    public const Status_Cancelled = 'cancelled';

    public const Statuses = [
        self::Status_Active => 'Aktif',
        self::Status_Churned => 'Berhenti',
        self::Status_Cancelled => 'Dibatalkan',
    ];
    
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}

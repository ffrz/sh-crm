<?php

namespace App\Models;

class CustomerService extends Model
{
    protected $fillable = [
        'customer_id',
        'service_id',
        'closing_id',
        'description',
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

    public function created_by_user()
    {
        return $this->belongsTo(User::class, 'created_by_uid');
    }

    public function updated_by_user()
    {
        return $this->belongsTo(User::class, 'updated_by_uid');
    }
    
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function closing()
    {
        return $this->belongsTo(Closing::class, 'closing_id');
    }
}

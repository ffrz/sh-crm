<?php

namespace App\Models;

class Closing extends Model
{
    protected $fillable = [
        'user_id',
        'customer_id',
        'service_id',
        'description',
        'date',
        'amount',
        'notes',
    ];

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

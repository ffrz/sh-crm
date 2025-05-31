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

    public function created_by_user()
    {
        return $this->belongsTo(User::class, 'created_by_uid');
    }

    public function updated_by_user()
    {
        return $this->belongsTo(User::class, 'updated_by_uid');
    }
    
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

    public function customerService()
    {
        return $this->hasOne(CustomerService::class, 'closing_id');
    }

    public static function booted()
    {
        static::saved(function ($item) {
            if ($item->customerService) {
                $item->customerService->closing_id = $item->id;
                $item->customerService->save();
            } else {
                $customerService = new CustomerService([
                    'customer_id' => $item->customer_id,
                    'service_id' => $item->service_id,
                    'closing_id' => $item->id,
                    'status' => CustomerService::Status_Active,
                    'description' => $item->description,
                    'start_date' => $item->date,
                    'notes' => $item->notes,
                ]);
                $customerService->save();
            }
        });

        static::deleted(function ($item) {
            if ($item->customerService) {
                $item->customerService->delete();
            }
        });
    }
}

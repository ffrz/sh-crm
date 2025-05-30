<?php

namespace App\Models;


class CustomerService extends Model
{
    protected $fillable = [
        'customer_id',
        'service_id',
        'engagement_level',
        'first_contact_date',
        'last_updated',
        'notes'
    ];
}

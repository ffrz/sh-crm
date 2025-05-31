<?php

namespace App\Models;

class Service extends Model
{
    protected $fillable = [
        'name',
        'active',
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
}

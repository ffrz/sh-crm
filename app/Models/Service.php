<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    protected $fillable = [
        'name',
        'active',
        'notes',
    ];
}

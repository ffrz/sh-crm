<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

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

    public static function activeServiceCount()
    {
        return DB::select(
            'select count(0) as count from services where active = 1'
        )[0]->count;
    }
}

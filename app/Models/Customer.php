<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'company',
        'business_type',
        'status',
        'source',
        'notes',
        'assigned_user_id',
    ];

    const Status_New = 'new';
    const Status_Contacted = 'contacted';
    const Status_ColdProspect = 'cold';
    const Status_HotProspect = 'hot';
    const Status_Converted = 'converted';
    const Status_Churned = 'churned';
    const Status_Inactive = 'inactive';

    const Statuses = [
        self::Status_New => 'New',
        self::Status_ColdProspect => 'Contacted',
        self::Status_ColdProspect => 'Cold Prospect',
        self::Status_HotProspect => 'Hot Prospect',
        self::Status_Converted => 'Converted',
        self::Status_Churned => 'Churned',
        self::Status_Inactive => 'Inactive',
    ];

    public static function activeCustomerCount()
    {
        return DB::select(
            "select count(0) as count from customers where status = 'converted'"
        )[0]->count;
    }

    public function assigned_user()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function created_by_user()
    {
        return $this->belongsTo(User::class, 'created_by_uid');
    }
    
    public function updated_by_user()
    {
        return $this->belongsTo(User::class, 'updated_by_uid');
    }
}

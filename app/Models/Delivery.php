<?php

namespace App\Models;

use App\Models\Unit;
use App\Models\User;
use App\Models\Transmittal;
use App\Models\DeliveryOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Delivery extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('transmittal_id', 'like', '%' . $search . '%');
        });
    }

    public function transmittal()
    {
        return $this->belongsTo(Transmittal::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'deliver_to');
    }

    public function delivery_orders()
    {
        return $this->hasMany(DeliveryOrder::class);
    }
}

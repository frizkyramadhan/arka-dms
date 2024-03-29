<?php

namespace App\Models;

use App\Models\User;
use App\Traits\Uuids;
use App\Models\Project;
use App\Models\Delivery;
use App\Models\Department;
use App\Models\DeliveryOrder;
use App\Models\TransmittalDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transmittal extends Model
{
    use HasFactory;
    use Uuids;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function transmittal_details()
    {
        return $this->hasMany(TransmittalDetail::class);
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    public function delivery_orders()
    {
        return $this->hasManyThrough(DeliveryOrder::class, Delivery::class);
    }
}

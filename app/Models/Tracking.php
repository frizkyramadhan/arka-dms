<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tracking extends Model
{
    // use HasFactory;
    use SoftDeletes;

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

    public function user()
    {
        return $this->belongsTo(User::class, 'tracking_by');
    }
}

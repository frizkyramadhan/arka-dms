<?php

namespace App\Models;

use App\Models\User;
use App\Models\Transmittal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function transmittal()
    {
        return $this->hasMany(Transmittal::class);
    }
}

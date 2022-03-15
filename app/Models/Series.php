<?php

namespace App\Models;

use App\Models\Transmittal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Series extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function transmittal()
    {
        return $this->hasMany(Transmittal::class);
    }
}

<?php

namespace App\Models;

use App\Models\Transmittal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransmittalDetail extends Model
{
    use HasFactory;

    protected $fillable = ['transmittal_id','qty','title','remarks'];

    public function transmittal()
    {
        return $this->belongsTo(Transmittal::class);
    }
}

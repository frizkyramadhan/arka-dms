<?php

namespace App\Models;

use App\Models\Transmittal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransmittalDetail extends Model
{
    // use HasFactory;
    use SoftDeletes;

    protected $fillable = ['transmittal_id','qty','title','remarks'];

    public function transmittal()
    {
        return $this->belongsTo(Transmittal::class);
    }
}

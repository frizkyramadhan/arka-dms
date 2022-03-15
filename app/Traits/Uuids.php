<?php

namespace App\Traits;

use App\Models\Transmittal;
use Illuminate\Support\Str;

trait Uuids
{
   /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            // generate uuid
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Str::uuid()->toString();
            }
            // generate receipt no
            // $model->receipt_no = Transmittal::where('series_id', $model->series_id)->max('receipt_no') + 1;
            // $model->receipt_full_no = $model->series->prefix . '-' . str_pad($model->receipt_no, 5, '0', STR_PAD_LEFT);
        });
    }

   /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return false;
    }

   /**
     * Get the auto-incrementing key type.
     *
     * @return string
     */
    public function getKeyType()
    {
        return 'string';
    }
}
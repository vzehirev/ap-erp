<?php

namespace App\Models;

use Carbon\Carbon;

class Salary extends NoTimestampsModel
{
    protected $fillable = ['date', 'worker_id', 'paid', 'price'];

    function getDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-M-Y');
    }

    function worker()
    {
        return $this->belongsTo(Worker::class);
    }
}

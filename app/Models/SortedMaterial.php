<?php

namespace App\Models;

use Carbon\Carbon;

class SortedMaterial extends NoTimestampsModel
{
    protected $fillable = ['sorted_on', 'partner_id', 'worker_id', 'quantity'];

    function getSortedOnAttribute($value)
    {
        return Carbon::parse($value)->format('d-M-Y');
    }

    function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    function partner()
    {
        return $this->belongsTo(Partner::class);
    }
}
<?php

namespace App\Models;

class Salary extends NoTimestampsModel
{
    protected $fillable = ['date', 'worker_id', 'paid', 'price'];

    function worker()
    {
        return $this->belongsTo(Worker::class);
    }
}

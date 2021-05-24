<?php

namespace App\Models;

class Prepaid extends NoTimestampsModel
{
    protected $table = 'prepaid';
    protected $fillable = ['paid_on', 'worker_id', 'price'];

    function worker()
    {
        return $this->belongsTo(Worker::class);
    }
}

<?php

namespace App\Models;

use Carbon\Carbon;

class GroundMaterial extends NoTimestampsModel
{
    function getGroundOnAttribute($value)
    {
        return Carbon::parse($value)->format('d-M-Y');
    }

    protected $fillable = ['ground_on', 'worker_id', 'quantity', 'material_id'];

    function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    function material()
    {
        return $this->belongsTo(Material::class);
    }
}

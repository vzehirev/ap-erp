<?php

namespace App\Models;

use Carbon\Carbon;

class GranularMaterial extends NoTimestampsModel
{
    protected $fillable = ['granular_on', 'worker_id', 'material_id', 'quantity'];

    function getGranularOnAttribute($value)
    {
        return Carbon::parse($value)->format('d-M-Y');
    }

    function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    function material()
    {
        return $this->belongsTo(Material::class);
    }
}

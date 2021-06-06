<?php

namespace App\Models;

use Carbon\Carbon;

class GranularMaterial extends NoTimestampsModel
{
    protected $fillable = ['granular_on', 'worker_id', 'from_material_id', 'to_material_id', 'quantity'];

    function getGranularOnAttribute($value)
    {
        return Carbon::parse($value)->format('d-M-Y');
    }

    function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    function from_material()
    {
        return $this->belongsTo(Material::class);
    }

    function to_material()
    {
        return $this->belongsTo(Material::class);
    }
}

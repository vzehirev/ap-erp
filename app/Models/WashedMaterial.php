<?php

namespace App\Models;

use Carbon\Carbon;

class WashedMaterial extends NoTimestampsModel
{
    protected $fillable = ['washed_on', 'worker_id', 'from_material_id', 'quantity_before', 'quantity', 'to_material_id'];

    function getWashedOnAttribute($value)
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

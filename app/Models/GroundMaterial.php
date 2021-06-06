<?php

namespace App\Models;

use Carbon\Carbon;

class GroundMaterial extends NoTimestampsModel
{
    protected $fillable = ['ground_on', 'worker_id', 'from_material_id', 'to_material_id', 'quantity'];

    function getGroundOnAttribute($value)
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

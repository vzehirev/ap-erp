<?php

namespace App\Models;

use App\Traits\UpdateMaterialsAvailableQuantities;
use Carbon\Carbon;

class WastedMaterial extends NoTimestampsModel
{
    use UpdateMaterialsAvailableQuantities;

    protected $fillable = ['wasted_on', 'worker_id', 'from_material_id', 'quantity'];

    function getWastedOnAttribute($value)
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
}

<?php

namespace App\Models;

use App\Traits\UpdateMaterialsAvailableQuantities;
use Carbon\Carbon;

class WastedMaterial extends NoTimestampsModel
{
    use UpdateMaterialsAvailableQuantities;

    protected $fillable = ['wasted_on', 'from_material_id', 'quantity'];

    function getWastedOnAttribute($value)
    {
        return Carbon::parse($value)->format('d-M-Y');
    }

    function workers()
    {
        return $this->belongsToMany(Worker::class);
    }

    function from_material()
    {
        return $this->belongsTo(Material::class);
    }
}

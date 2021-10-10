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

    function from_materials()
    {
        return $this->belongsToMany(Material::class, 'granular_material_from_material', 'granular_material_id', 'from_material_id')->withPivot('from_material_quantity');
    }

    function to_material()
    {
        return $this->belongsTo(Material::class);
    }

    function wasted_materials()
    {
        return $this->hasMany(WastedMaterial::class);
    }
}

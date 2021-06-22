<?php

namespace App\Models;

use Carbon\Carbon;

class SortedMaterial extends NoTimestampsModel
{
    protected $fillable = ['sorted_on', 'from_material_id', 'to_material_id', 'quantity'];

    function getSortedOnAttribute($value)
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

    function to_material()
    {
        return $this->belongsTo(Material::class);
    }

    function wasted_material()
    {
        return $this->hasOne(WastedMaterial::class);
    }
}

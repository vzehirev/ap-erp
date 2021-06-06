<?php

namespace App\Models;

use Carbon\Carbon;

class WastedMaterial extends NoTimestampsModel
{
    protected $fillable = ['wasted_on', 'quantity', 'sorted_material_id', 'washed_material_id', 'granular_material_id', 'from_material_id'];

    function getWastedOnAttribute($value)
    {
        return Carbon::parse($value)->format('d-M-Y');
    }

    function sorted_material()
    {
        return $this->belongsTo(SortedMaterial::class);
    }

    function washed_material()
    {
        return $this->belongsTo(WashedMaterial::class);
    }

    function granular_material()
    {
        return $this->belongsTo(GranularMaterial::class);
    }

    function from_material()
    {
        return $this->belongsTo(Material::class);
    }
}

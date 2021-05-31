<?php

namespace App\Models;

use App\Traits\UpdateMaterialsAvailableQuantities;
use Carbon\Carbon;

class SortedMaterial extends NoTimestampsModel
{
    use UpdateMaterialsAvailableQuantities;

    protected $fillable = ['sorted_on', 'partner_id', 'worker_id', 'from_material_id', 'to_material_id', 'quantity'];

    function getSortedOnAttribute($value)
    {
        return Carbon::parse($value)->format('d-M-Y');
    }

    function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    function partner()
    {
        return $this->belongsTo(Partner::class);
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

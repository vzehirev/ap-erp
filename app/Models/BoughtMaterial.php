<?php

namespace App\Models;

use Carbon\Carbon;

class BoughtMaterial extends NoTimestampsModel
{
    protected $fillable = ['bought_on', 'partner_id', 'price', 'quantity', 'material_id', 'invoice_num'];

    function getBoughtOnAttribute($value)
    {
        return Carbon::parse($value)->format('d-M-Y');
    }

    function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    function material()
    {
        return $this->belongsTo(Material::class);
    }
}

<?php

namespace App\Models;

use Carbon\Carbon;

class SoldMaterial extends NoTimestampsModel
{
    protected $fillable = ['sold_on', 'partner_id', 'material_id', 'quantity', 'price', 'paid', 'invoice_num'];

    function getSoldOnAttribute($value)
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

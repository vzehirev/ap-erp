<?php

namespace App\Models;

class Partner extends NoTimestampsModel
{
    protected $fillable = ['name'];

    function boughtMaterials()
    {
        return $this->hasMany(BoughtMaterial::class);
    }
}

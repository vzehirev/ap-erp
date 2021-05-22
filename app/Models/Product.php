<?php

namespace App\Models;

class Product extends NoTimestampsModel
{
    protected $fillable = ['name', 'code',];

    function boughtMaterials()
    {
        return $this->hasMany(BoughtMaterial::class);
    }
}

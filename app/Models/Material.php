<?php

namespace App\Models;

class Material extends NoTimestampsModel
{
    protected $fillable = ['name', 'code',];

    function boughtMaterials()
    {
        return $this->hasMany(BoughtMaterial::class);
    }
}

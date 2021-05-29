<?php

namespace App\Models;

class Material extends NoTimestampsModel
{
    protected $fillable = ['name', 'code',];

    function getNameAndCodeAttribute()
    {
        return $this->code ? "$this->name ($this->code)" : $this->name;
    }

    function increaseAvailableQuantity($quantity)
    {
        $this->available_quantity += $quantity;
        $this->save();
    }

    function decreaseAvailableQuantity($quantity)
    {
        $this->available_quantity -= $quantity;
        $this->save();
    }
}

<?php

namespace App\Models;

use Carbon\Carbon;

class Expense extends NoTimestampsModel
{
    protected $fillable = ['made_on', 'type', 'price'];

    function getMadeOnAttribute($value)
    {
        return Carbon::parse($value)->format('d-M-Y');
    }
}

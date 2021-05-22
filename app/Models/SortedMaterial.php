<?php

namespace App\Models;

class SortedMaterial extends NoTimestampsModel
{
    protected $fillable = ['sorted_on', 'partner_id', 'worker_id', 'quantity'];
}

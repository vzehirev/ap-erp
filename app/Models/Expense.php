<?php

namespace App\Models;

class Expense extends NoTimestampsModel
{
    protected $fillable = ['made_on', 'type', 'price'];
}

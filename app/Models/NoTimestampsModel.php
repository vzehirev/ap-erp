<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoTimestampsModel extends Model
{
    use HasFactory;

    public $timestamps = false;
}

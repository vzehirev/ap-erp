<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoughtMaterial extends Model
{
    use HasFactory;

    protected $fillable = ['bought_on', 'partner_id', 'price', 'quantity', 'product_id', 'invoice_num'];

    public $timestamps = false;

    function partners()
    {
        return $this->belongsTo(Partner::class);
    }
}

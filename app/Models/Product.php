<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'image',
        'stock',
        'vendor_id',
    ];

    public function vendor()
{
    return $this->belongsTo(Vendor::class);
}

}

<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{

    protected $fillable = [
        'shop_name',
        'gst_number',
        'business_address',
        'is_active',
        'is_verified',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}

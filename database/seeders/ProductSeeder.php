<?php

namespace Database\Seeders;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendors = Vendor::all();

    foreach ($vendors as $vendor) {
        Product::create([
            'name' => 'Product 1 by ' . $vendor->user->name,
            'price' => 100 + rand(5, 10),
            'stock' => 10 + rand(5, 10),
            'image' => 'products/product1.jpg',
            'vendor_id' => $vendor->id,
        ]);

        Product::create([
            'name' => 'Product 2 by ' . $vendor->user->name,
            'price' => 150 + rand(5, 10),
            'stock' => 15 + rand(5, 10),
            'image' => 'products/product2.jpg',
            'vendor_id' => $vendor->id,
        ]);
    }
    }
}

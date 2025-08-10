<?php

namespace Database\Seeders;
use App\Models\Vendor;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendorUsers = User::where('role', 'vendor')->get();

        foreach ($vendorUsers as $user) {
            Vendor::create([
                'user_id' => $user->id,
                'shop_name' => $user->name . "'s Shop",
                'gst_number' => 'GST' . rand(1000, 9999) . 'XYZ',
                'business_address' => 'Address for ' . $user->name,
            ]);
        }
    }
}

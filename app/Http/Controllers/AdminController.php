<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function vendors()
    {
        $products = Product::count();
        $vendors = Vendor::with('user')->get();
        return view('admin.adminVendors', compact('products', 'vendors'));
    }

    public function toggleStatus($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->is_active = !$vendor->is_active;
        $vendor->save();

        return redirect()->back()->with('success', 'Vendor status updated successfully.');
    }

    public function viewProducts()
    {
        // Products with vendor & vendor->user relation
        $products = Product::with(['vendor.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.adminVendorProducts', compact('products'));
    }

    public function vendorPage($vendorId)
    {
        $vendor = Vendor::with('products')->findOrFail($vendorId);
        return view('admin.adminVendorView', compact('vendor'));
    }
}

<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $users = User::where('role', 'customer')->count();
            $products = Product::count();
            $vendors = Vendor::count();
        } elseif ($user->role === 'vendor') {
            $vendorId = $user->vendorProfile->id;
            $users = null;
            $products = Product::where('vendor_id', $vendorId)->count();

            $orderCount = OrderItem::where('vendor_id', $vendorId)
            ->distinct('order_id')
            ->count('order_id');

            return view('admin.dashboard', compact('users', 'products', 'orderCount'));
        } else {
            return redirect('/');
        }
        return view('admin.dashboard', compact('users', 'products', 'vendors'));

    }

    public function vendors()
    {
        $products = Product::count();
        $vendors = Vendor::with('user')->get();

        return view('admin.adminVendors', compact('products', 'vendors'));


    }
}

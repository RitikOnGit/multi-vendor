<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Product;
use App\Models\Vendor;
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
            $users = null;
            $products = Product::where('vendor_id', $user->id)->count();
            $vendors = null;
        } else {
            return redirect('/');
        }

        return view('admin.dashboard', compact('users', 'products', 'vendors'));
    }
}

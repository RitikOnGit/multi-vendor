<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'vendor') {
            abort(403, 'Not allowed');
        }
        $user = auth()->user();
        $vendorId = $user->vendorProfile->id;

        $products = Product::where('vendor_id', $vendorId)->get();
        return view('admin.vendorProducts', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image'
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $data['vendor_id'] = auth()->user()->vendorProfile->id;;
        Product::create($data);
        return redirect()->back()->with('success', 'Product added successfully!');
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
class HomeController extends Controller
{
    public function index()
    {

        $products = Product::get();

        return view('pages.index', compact('products'));

    }

    /**
     * Cart update method.
     */
    public function addToCart(Request $request)
    {
        $cart = session()->get('cart', []);

        $productId = $request->id;

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += 1;
        } else {
            $cart[$productId] = [
                'id' => $request->id,
                'name' => $request->name,
                'price' => $request->price,
                'image' => $request->image,
                'quantity' => 1,
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'cart_count' => count($cart),
            'cart' => $cart,
        ]);
    }

    public function getCart()
    {
        return response()->json(session()->get('cart', []));
    }

    public function removeFromCart(Request $request)
{
    $cart = session()->get('cart', []);
    $id = $request->id;

    if (isset($cart[$id])) {
        unset($cart[$id]);
        session()->put('cart', $cart);
    }

    return response()->json(['success' => true, 'cart' => $cart]);
}

public function updateQuantity(Request $request)
{
    $cart = session()->get('cart', []);
    $id = $request->id;
    $quantity = $request->quantity;

    if (isset($cart[$id])) {
        $cart[$id]['quantity'] = max(1, (int)$quantity);
        session()->put('cart', $cart);
    }

    return response()->json(['success' => true, 'cart' => $cart]);
}

    public function create()
    {
        //
    }

    public function cart_update(Request $request)
{
    $cart = session('cart', []);

    $productId = $request->input('id');
    $quantity = $request->input('quantity');

    if (isset($cart[$productId])) {
        $cart[$productId]['quantity'] = $quantity;
    }

    session()->put('cart', $cart);
    return response()->json(['success' => true]);
}

public function cart_remove(Request $request)
{
    $cart = session('cart', []);
    $productId = $request->input('id');

    unset($cart[$productId]);

    session()->put('cart', $cart);
    return response()->json(['success' => true]);
}



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

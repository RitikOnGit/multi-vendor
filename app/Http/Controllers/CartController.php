<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartRequest;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    // show cart (grouped by vendor)
    public function index()
    {
        $groups = $this->cartService->getGroupedCart();
        return view('pages.cart', compact('groups'));
    }

    public function getCartData()
    {
        $cart = $this->cartService->getCartItems(); // Guest = session, Logged-in = DB
        return response()->json(['cart' => $cart]);
    }
    public function store(AddToCartRequest $request)
    {
        $this->cartService->addToCart($request->product_id, $request->quantity);
        return response()->json(['success' => true,'message' => 'Added to cart.']);
    }

    public function update(UpdateCartRequest $request, $id)
    {
        $this->cartService->updateQuantity($id, $request->quantity);
        return back()->with('success','Cart updated.');
    }

    public function destroy($id)
    {
        $this->cartService->removeItem($id);
        return back()->with('success','Item removed.');
    }
}

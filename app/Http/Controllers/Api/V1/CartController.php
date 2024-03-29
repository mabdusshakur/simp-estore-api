<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\UpdateCartRequest;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CartResource::collection(Cart::where('user_id', auth()->user()->id)->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCartRequest $request)
    {
        try {
            $request->validated();
            $cart = Cart::updateOrCreate(
                [
                    'user_id' => auth()->user()->id,
                    'product_id' => $request->product_id,
                ],
                [
                    'quantity' => $request->quantity,
                ]
            );
            $message = $cart->wasRecentlyCreated ? 'Cart created successfully' : 'Cart updated successfully';
            return new CartResource($cart);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCartRequest $request, Cart $cart)
    {
        try {
            $request->validated();
            $cart->update([
                'quantity' => $request->quantity,
            ]);
            return new CartResource($cart);
        } catch (\Throwable $th) {
            return response()->json([
                    'status' => 'error',
                    'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * cart item increment
     */
    public function increment(Cart $cart)
    {
        try {
            $cart->update([
                'quantity' => $cart->quantity + 1,
            ]);
            return new CartResource($cart);
        } catch (\Throwable $th) {
            return response()->json([
                    'status' => 'error',
                    'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * cart item decrement
     */
    public function decrement(Cart $cart)
    {
        try {
            if ($cart->quantity > 1) {

                $cart->update([
                    'quantity' => $cart->quantity - 1,
                ]);
            }
            return new CartResource($cart);
        } catch (\Throwable $th) {
            return response()->json([
                    'status' => 'error',
                    'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        try {
            $cart->delete();
            return response()->json([
                    'status' => 'success',
                    'message' => 'Cart deleted successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                    'status' => 'error',
                    'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove all wishlist
     */
    public function destroyAll()
    {
        try {
            Cart::where('user_id', auth()->user()->id)->delete();
            return response()->json([
                    'status' => 'success',
                    'message' => 'Cart emptied successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                    'status' => 'error',
                    'message' => $th->getMessage(),
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Cart;
use App\Models\Order;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\OrderItems;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreOrderRequest $request)
    {
        try {
            $request->validated();
            $cartItems = Cart::where('user_id', auth()->user()->id)->get();
            $totalPrice = 0;
            foreach ($cartItems as $cartItem) {
                $totalPrice += $cartItem->product->sale_price ?? $cartItem->product->regular_price * $cartItem->quantity;
            }

            if($request->payment_method == 'cod')
            {
                $order = Order::create([
                    'user_id' => auth()->user()->id,
                    'total' => $totalPrice,
                    'status' => $request->status,
                    'payment_method' => $request->payment_method,
                ]);
                if($order)
                {
                    foreach ($cartItems as $cartItem) {
                        $orderItem = new OrderItems();
                        $orderItem->order_id = $order->id;
                        $orderItem->product_id = $cartItem->product_id;
                        $orderItem->quantity = $cartItem->quantity;
                        $orderItem->price = $cartItem->product->sale_price ?? $cartItem->product->regular_price;
                        $orderItem->save();
                        $cartItem->product->stock -= $cartItem->quantity;
                        $cartItem->product->sold_count += $cartItem->quantity;
                        $cartItem->product->save();
                        $cartItem->delete();
                    }

                    return response()->json([
                        'data' => [
                            'status' => 'success',
                            'message' => 'Order created successfully',
                            'order' => $order,
                        ],
                    ], 201);
                }
            }

        } catch (\Throwable $th) {
            return response()->json([
                'data' => [
                    'status' => 'error',
                    'message' => $th->getMessage(),
                ],
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}

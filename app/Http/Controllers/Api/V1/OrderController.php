<?php

namespace App\Http\Controllers\Api\v1;

use Validator;
use Stripe\Stripe;
use App\Models\Cart;
use App\Models\Order;
use Stripe\PaymentIntent;
use App\Models\OrderItems;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\OrderResource;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()->role == 'admin') {
            return OrderResource::collection(Order::all());
        } else {
            return OrderResource::collection(Order::where('user_id', auth()->user()->id)->get());
        }
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

            if ($request->payment_method == 'cod') {
                $order = Order::create([
                    'user_id' => auth()->user()->id,
                    'total' => $totalPrice,
                    'status' => $request->status,
                    'payment_method' => $request->payment_method,
                ]);
                if ($order) {
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
                    $mail_data = [
                        'subject' => 'Order created successfully',
                        'customer_name' => auth()->user()->name,
                        'order' => $order,
                    ];
                    Mail::to(auth()->user()->email)->send(new \App\Mail\OrderStatusMail($mail_data));
                    return response()->json([
                        'data' => [
                            'status' => 'success',
                            'message' => 'Order created successfully',
                            'order' => $order,
                        ],
                    ], 201);
                }
            } else if ($request->payment_method == 'stripe') {

                try {

                    $validatedData = Validator::make($request->all(), [
                        'card_number' => 'required',
                        'exp_month' => 'required',
                        'exp_year' => 'required',
                        'cvc' => 'required',
                    ]);
                    if ($validatedData->fails()) {
                        return response()->json([
                            'data' => [
                                'status' => 'error',
                                'message' => $validatedData->errors()->first(),
                            ],
                        ], 400);
                    }

                    $order = Order::create([
                        'user_id' => auth()->user()->id,
                        'total' => $totalPrice,
                        'status' => $request->status,
                        'payment_method' => $request->payment_method,
                    ]);
                    if ($order) {
                        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
                        $stripe_card = $stripe->tokens->create([
                            'card' => [
                                'number' => $request->card_number,
                                'exp_month' => $request->exp_month,
                                'exp_year' => $request->exp_year,
                                'cvc' => $request->cvc,
                            ]
                        ]);
                        $stripe_response = $stripe->charges->create([
                            'amount' => $totalPrice * 100,
                            'currency' => 'usd',
                            'source' => $stripe_card->id,
                            'description' => 'Order ID : ' . $order->id . ' User Name : ' . auth()->user()->name . ' User Email : ' . auth()->user()->email . ' User Phone : ' . auth()->user()->phone_number,
                        ]);

                        if ($stripe_response->status == 'succeeded') {
                            $order->status = 'completed';
                            $order->save();

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
                        } else {
                            return response()->json([
                                'data' => [
                                    'status' => 'error',
                                    'message' => 'Payment failed',
                                ],
                            ], 500);
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
            } else if ($request->payment_method == 'stripe_intent') {
                try {
                    $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
                    $intentResponse = $stripe->paymentIntents->create([
                        'amount' =>  $totalPrice,
                        'currency' => 'usd',
                        'automatic_payment_methods' => ['enabled' => true],
                    ]);
                    $order = Order::create([
                        'user_id' => auth()->user()->id,
                        'total' => $totalPrice,
                        'status' => 'pending',
                        'payment_method' => $request->payment_method,
                        'transaction_id' => $intentResponse->id,
                    ]);
                    return response()->json([
                        'data' => [
                            'status' => 'success',
                            'message' => 'Order on pending payment',
                            'order' => $order,
                            'client_secret' => $intentResponse->client_secret,
                        ],
                    ], 201);
                } catch (\Throwable $th) {
                    return response()->json([
                        'data' => [
                            'status' => 'error',
                            'message' => $th->getMessage(),
                        ],
                    ], 500);
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
     * Confirm stripe intent payment
     */
    public function confirmStripeIntentPayment(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $paymentIntent = PaymentIntent::retrieve($request->payment_intent_client_id);
        if ($paymentIntent->status === 'succeeded') {     
            $cartItems = Cart::where('user_id', auth()->user()->id)->get();
            $order = Order::where('transaction_id', $request->payment_intent_client_id)->first();
            $order->status = 'completed';
            $order->save();
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
                'success' => true,
                'order' => "Order created successfully, payment intent status : " . $paymentIntent->status,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Payment failed',
            ]);
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

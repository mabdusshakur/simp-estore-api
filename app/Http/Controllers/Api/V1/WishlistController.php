<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Resources\WishlistResource;
use App\Models\Wishlist;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWishlistRequest;
use App\Http\Requests\UpdateWishlistRequest;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return WishlistResource::collection(Wishlist::all());
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
    public function store(StoreWishlistRequest $request)
    {
        try {
            $request->validated();
            $wishlist = Wishlist::create([
                'user_id' => auth()->user()->id,
                'product_id' => $request->product_id,
            ]);
            return new WishlistResource([$wishlist, 'status' => 'success', 'message' => 'Wishlist created successfully']);
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
    public function show(Wishlist $wishlist)
    {
        try {
            return new WishlistResource($wishlist);
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
     * Show the form for editing the specified resource.
     */
    public function edit(Wishlist $wishlist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWishlistRequest $request, Wishlist $wishlist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wishlist $wishlist)
    {
        try {
            $wishlist->delete();
            return response()->json([
                'data' => [
                    'status' => 'success',
                    'message' => 'Wishlist deleted successfully',
                ],
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => [
                    'status' => 'error',
                    'message' => $th->getMessage(),
                ],
            ], 500);
        }
    }
}

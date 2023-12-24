<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Product;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ProductResource::collection(Product::all());
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
    public function store(StoreProductRequest $request)
    {
        try {
            $request->validated();
            $product = Product::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'regular_price' => $request->regular_price,
                'sale_price' => $request->sale_price,
                'category_id' => $request->category_id,
                'subcategory_id' => $request->subcategory_id,
                'status' => $request->status,
                'stock' => $request->stock,
            ]);
            return new ProductResource([$product, 'status' => 'success', 'message' => 'Product created successfully']);
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
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}

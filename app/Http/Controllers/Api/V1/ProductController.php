<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Image;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Resources\ProductResource;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::when($request->search, function ($query) use ($request) {
            $query->where('name', 'like', "%{$request->search}%")->orWhere('description', 'like', "%{$request->search}%");
        })->when($request->sort, function ($query) use ($request) {
            $query->orderBy($request->sort, $request->order);
        })->paginate($request->paginate ?? 10);

        return ProductResource::collection($products);
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
            $images = $request->file('images');
            if($images){
                foreach ($images as $image) {
                    $fileName = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
                    $filePath = $image->move(public_path('images\product'), $fileName);
                    $product->images()->create([
                        'path' => $filePath,
                    ]);
                }
            }
            Category::where('id', $request->category_id)->increment('products_count', 1);
            SubCategory::where('id', $request->subcategory_id)->increment('products_count', 1);
            return new ProductResource($product);
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
    public function show(Product $product)
    {
        try {
            $product->view_count = $product->view_count + 1;
            $product->save();
            return new ProductResource($product);
        } catch (\Throwable $th) {
            return response()->json([
                    'status' => 'error',
                    'message' => $th->getMessage(),
            ], 500);
        }
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
        try {
            $request->validated();
            $product->update([
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
            $images = $request->file('images');
            if($images){
                foreach ($images as $image) {
                    $fileName = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
                    $filePath = $image->move(public_path('images\product'), $fileName);
                    $product->images()->create([
                        'path' => $filePath,
                    ]);
                }
            }
            return new ProductResource($product);
        } catch (\Throwable $th) {
            return response()->json([
                    'status' => 'error',
                    'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete specific image.
    */

    public function deleteImage(Request $request)
    {
        try {
            $image = Image::where('id', $request->image_id)->first();
            if (File::exists($image->path)) {
                unlink($image->path);
            }
            $image->delete();
            return response()->json([
                    'status' => 'success',
                    'message' => 'Image deleted successfully',
            ], 200);
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
    public function destroy(Product $product)
    {
        try {
            Category::where('id', $product->category_id)->decrement('products_count', 1);
            SubCategory::where('id', $product->subcategory_id)->decrement('products_count', 1);
            $product->delete();
            return response()->json([
                    'status' => 'success',
                    'message' => 'Product deleted successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                    'status' => 'error',
                    'message' => $th->getMessage(),
            ], 500);
        }
    }
}

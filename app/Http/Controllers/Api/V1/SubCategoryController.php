<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Resources\SubCategoryResource;
use App\Http\Requests\StoreSubCategoryRequest;
use App\Http\Requests\UpdateSubCategoryRequest;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $subcategories = SubCategory::when($request->search, function ($query) use ($request) {
            $query->where('name', 'like', "%{$request->search}%");
        })->when($request->sort, function ($query) use ($request) {
            $query->orderBy($request->sort, $request->order);
        })->paginate($request->paginate ?? 10);
        return SubCategoryResource::collection($subcategories);
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
    public function store(StoreSubCategoryRequest $request)
    {
        try {
            $request->validated();
            $subCategory = SubCategory::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'category_id' => $request->category_id,
            ]);
            Category::where('id', $request->category_id)->increment('sub_categories_count', 1);
            return response()->json([
                    'status' => 'success',
                    'message' => 'Sub Category Created successfully',
                    'data' => new SubCategoryResource($subCategory),
            ], 201);
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
    public function show(SubCategory $subCategory)
    {
        try {
            return new SubCategoryResource($subCategory);
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
    public function edit(SubCategory $subCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubCategoryRequest $request, SubCategory $subCategory)
    {
        $request->validated();
        try {
            $subCategory->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'category_id' => $request->category_id,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Sub Category Updated successfully',
                'data' => new SubCategoryResource($subCategory),
        ], 201);
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
    public function destroy(SubCategory $subCategory)
    {
        try {
            Category::where('id', $subCategory->category_id)->decrement('sub_categories_count', 1);
            $subCategory->delete();
            return response()->json([
                    'status' => 'success',
                    'message' => 'SubCategory deleted successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                    'status' => 'error',
                    'message' => $th->getMessage(),
            ], 500);
        }
    }
}

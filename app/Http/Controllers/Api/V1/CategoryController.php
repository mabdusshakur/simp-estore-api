<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::when($request->search, function ($query) use ($request) {
            $query->where('name', 'like', "%{$request->search}%");
        })->when($request->sort, function ($query) use ($request) {
            $query->orderBy($request->sort, $request->order);
        })->paginate($request->paginate ?? 10);

        return CategoryResource::collection($categories);
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
    public function store(StoreCategoryRequest $request)
    {
        $request->validated();
        try {
            $category = Category::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
            ]);
            return response()->json([
                    'status' => 'success',
                    'message' => 'Category created successfully',
                    'data' => new CategoryResource($category),
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
    public function show(Category $category)
    {
        try {
            return new CategoryResource($category);
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
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $request->validated();
        try {
            $category->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
            ]);
            return new CategoryResource([$category, 'status' => 'success', 'message' => 'Category updated successfully']);
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
    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return response()->json([
                    'status' => 'success',
                    'message' => 'Category deleted successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                    'status' => 'error',
                    'message' => $th->getMessage(),
            ], 500);
        }
    }
}

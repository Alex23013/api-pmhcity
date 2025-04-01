<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Validator;

class CategoryController extends BaseController
{
    /**
     * Display a listing of the categories.
     */
    public function index()
    {
        $categories = Category::with(['subcategories' => function ($query) {
            $query->orderBy('id');
        }])->orderBy('id')->get();
        return CategoryResource::collection($categories);
    }

    public function listNames()
    {
        $categories = Category::select('id', 'name')->get();
        return response()->json($categories);
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name|max:255',
            'cover_image' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error.',
                'errors' => $validator->errors(),
            ], 422);
        }
        $categoryData = $request->only(['name']);

        if ($request->hasFile('cover_image')) {
            // Store the file and get the path
            $imagePath = $request->file('cover_image')->store('cover_photos', 'public');
            $categoryData['cover_image'] = $imagePath;
        } elseif ($request->filled('cover_image')) {
            // Use the provided value (e.g., a URL)
            $categoryData['cover_image'] = $request->input('cover_image');
        }

        $category = Category::create($categoryData);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully.',
            'data' => new CategoryResource($category),
        ], 201);
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category)
    {
        return new CategoryResource($category->load('subcategories'));
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name,' . $category->id . '|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $category->update($request->only(['name']));

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully.',
            'data' => new CategoryResource($category),
        ]);
    }

    /**
     * Remove the specified category.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully.',
        ]);
    }
}

<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\Subcategory;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\JsonResponse;

class SubcategoryController extends BaseController
{
    /**
     * Get all subcategories.
     */
    public function index(): JsonResponse
    {
        $subcategories = Subcategory::with('category:id,name')->get();
        
        return response()->json($subcategories);
    }

    /**
     * Store a new subcategory.
     */
    public function store(Request $request): JsonResponse
    {
        // Validate request data
        $request->validate([
            'name' => 'required|string|max:255|unique:subcategories,name',
            'category_id' => 'required|exists:categories,id',
            'cover_image' => 'nullable',
        ]);
        $subcategoryData = $request->only(['name', 'category_id']);

        if ($request->hasFile('cover_image')) {
            // Store the file and get the path
            $imagePath = $request->file('cover_image')->store('cover_photos', 'public');
            $subcategoryData['cover_image'] = $imagePath;
        } elseif ($request->filled('cover_image')) {
            // Use the provided value (e.g., a URL)
            $subcategoryData['cover_image'] = $request->input('cover_image');
        }

        $subcategory = Subcategory::create($subcategoryData);

        // Return success response
        return response()->json([
            'message' => 'Subcategory created successfully.',
            'subcategory' => $subcategory,
        ], 201);
    }

    /**
     * Get a single subcategory by ID.
     */
    public function show($id): JsonResponse
    {
        $subcategory = Subcategory::with('category:id,name, cover_image')->find($id);

        if (!$subcategory) {
            return response()->json(['message' => 'Subcategory not found'], 404);
        }

        return response()->json($subcategory);
    }

    public function listProductsBySubcategory($subcategoryId)
    {
        $subcategory = Subcategory::find($subcategoryId);
        if (!$subcategory) {
            return response()->json([
                'status' => false,
                'message' => 'Subcategory not found.'
            ], 404);
        }

        $products = $subcategory->products()->with(['brand', 'material', 'status_product', 'category', 'subcategory', 'photoProducts'])->get();
        
        return response()->json([
            'status' => true,
            'message' => 'Products retrieved successfully.',
            'data' => [
                'products' => ProductResource::collection($products),
                'subcategory' => [
                        "cover_image" => $subcategory->cover_image,
                        "name" => $subcategory->name
                        ]
                ]
        ], 200);
    }
    /**
     * Update a subcategory.
     */
    public function update(Request $request, $id): JsonResponse
    {
        // Find subcategory
        $subcategory = Subcategory::find($id);

        if (!$subcategory) {
            return response()->json(['message' => 'Subcategory not found'], 404);
        }

        // Validate input
        $request->validate([
            'name' => 'sometimes|string|max:255|unique:subcategories,name,' . $id,
            'category_id' => 'sometimes|exists:categories,id',
        ]);

        // Update subcategory
        $subcategory->update($request->only('name', 'category_id'));

        return response()->json([
            'message' => 'Subcategory updated successfully.',
            'subcategory' => $subcategory,
        ]);
    }

    /**
     * Delete a subcategory.
     */
    public function destroy($id): JsonResponse
    {
        $subcategory = Subcategory::find($id);

        if (!$subcategory) {
            return response()->json(['message' => 'Subcategory not found'], 404);
        }

        $subcategory->delete();

        return response()->json(['message' => 'Subcategory deleted successfully.']);
    }
}

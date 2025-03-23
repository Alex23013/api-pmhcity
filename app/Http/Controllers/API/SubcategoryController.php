<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\Subcategory;
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
        ]);

        // Create the subcategory
        $subcategory = Subcategory::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
        ]);

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
        $subcategory = Subcategory::with('category:id,name')->find($id);

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

        $user = $subcategory->user;

        $products = $user->products()->with(['brand', 'material', 'status_product', 'category', 'subcategory', 'photoProducts'])->get();
        
        return response()->json([
            'status' => true,
            'message' => 'Products retrieved successfully.',
            'data' => [
                'products' => ProductResource::collection($products),
                'store' => [
                        "logo" => $subcategory->logo,
                        "name" => $subcategory->name,
                        "phone" => $user->phone,
                        "city" => $user->city->name,
                        "metropole" => $user->city->metropole->name,
                        "banner" => $subcategory->banner,
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

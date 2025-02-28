<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;


use App\Models\Brand;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\BrandResource;

class BrandController extends BaseController
{

    public function index()
    {
        $brands = Brand::all();
        return BrandResource::collection($brands);
    }

    public function listNames()
    {
        $brands = Brand::select('id', 'name')->get();
        return response()->json($brands);
    }

    /**
     * Store a newly created brand.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:brands,name|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $brand = Brand::create($request->only(['name']));

        return response()->json([
            'success' => true,
            'message' => 'Brand created successfully.',
            'data' => new BrandResource($brand),
        ], 201);
    }

    /**
     * Display the specified Brand.
     */
    public function show(Brand $brand)
    {
        return new BrandResource($brand);
    }

    /**
     * Update the specified brand.
     */
    public function update(Request $request, Brand $brand)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:brands,name,' . $brand->id . '|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $brand->update($request->only(['name']));

        return response()->json([
            'success' => true,
            'message' => 'Brand updated successfully.',
            'data' => new BrandResource($brand),
        ]);
    }

    /**
     * Remove the specified brand.
     */
    public function destroy(Brand $brand)
    {
        $brand->delete();

        return response()->json([
            'success' => true,
            'message' => 'Brand deleted successfully.',
        ]);
    }
}

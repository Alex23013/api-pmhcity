<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;


use App\Models\StatusProduct;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\StatusProductResource;

class StatusProductController extends BaseController
{

    public function index()
    {
        $statusProducts = StatusProduct::all();
        return StatusProductResource::collection($statusProducts);
    }

    public function listNames()
    {
        $statusProducts = StatusProduct::select('id', 'name')->get();
        return response()->json($statusProducts);
    }

    /**
     * Store a newly created statusProduct.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:statusProducts,name|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $statusProduct = StatusProduct::create($request->only(['name']));

        return response()->json([
            'success' => true,
            'message' => 'StatusProduct created successfully.',
            'data' => new StatusProductResource($statusProduct),
        ], 201);
    }

    /**
     * Display the specified StatusProduct.
     */
    public function show(StatusProduct $statusProduct)
    {
        return new StatusProductResource($statusProduct);
    }

    /**
     * Update the specified statusProduct.
     */
    public function update(Request $request, StatusProduct $statusProduct)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:statusProducts,name,' . $statusProduct->id . '|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $statusProduct->update($request->only(['name']));

        return response()->json([
            'success' => true,
            'message' => 'StatusProduct updated successfully.',
            'data' => new StatusProductResource($statusProduct),
        ]);
    }

    /**
     * Remove the specified statusProduct.
     */
    public function destroy(StatusProduct $statusProduct)
    {
        $statusProduct->delete();

        return response()->json([
            'success' => true,
            'message' => 'StatusProduct deleted successfully.',
        ]);
    }
}

<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;


use App\Models\Size;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\SizeResource;

class SizeController extends BaseController
{

    public function index()
    {
        $sizes = Size::all();
        return SizeResource::collection($sizes);
    }

    public function listNames()
    {
        $sizes = Size::select('id', 'name')->get();
        return response()->json($sizes);
    }

    public function listSizesBySubcategory($id)
    {
        $sizes = Size::select('id', 'name')->get();
        return SizeResource::collection($sizes);
    }

    /**
     * Store a newly created size.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:sizes,name|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $size = Size::create($request->only(['name']));

        return response()->json([
            'success' => true,
            'message' => 'Size created successfully.',
            'data' => new SizeResource($size),
        ], 201);
    }

    /**
     * Display the specified Size.
     */
    public function show(Size $size)
    {
        return new SizeResource($size);
    }

    /**
     * Update the specified size.
     */
    public function update(Request $request, Size $size)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:sizes,name,' . $size->id . '|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $size->update($request->only(['name']));

        return response()->json([
            'success' => true,
            'message' => 'Size updated successfully.',
            'data' => new SizeResource($size),
        ]);
    }

    /**
     * Remove the specified size.
     */
    public function destroy(Size $size)
    {
        $size->delete();

        return response()->json([
            'success' => true,
            'message' => 'Size deleted successfully.',
        ]);
    }
}

<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;


use App\Models\Color;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ColorResource;

class ColorController extends BaseController
{

    public function index()
    {
        $colors = Color::all();
        return ColorResource::collection($colors);
    }

    public function listNames()
    {
        $colors = Color::select('id', 'name','hex_code')->get();
        return response()->json($colors);
    }

    /**
     * Store a newly created color.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:colors,name|max:255',
            'hex_code' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $color = Color::create($request->only(['name','hex_code']));

        return response()->json([
            'success' => true,
            'message' => 'Color created successfully.',
            'data' => new ColorResource($color),
        ], 201);
    }

    /**
     * Display the specified Color.
     */
    public function show(Color $color)
    {
        return new ColorResource($color);
    }

    /**
     * Update the specified color.
     */
    public function update(Request $request, Color $color)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:colors,name,' . $color->id . '|max:255',
            'hex_code' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $color->update($request->only(['name','hex_code']));

        return response()->json([
            'success' => true,
            'message' => 'Color updated successfully.',
            'data' => new ColorResource($color),
        ]);
    }

    /**
     * Remove the specified color.
     */
    public function destroy(Color $color)
    {
        $color->delete();

        return response()->json([
            'success' => true,
            'message' => 'Color deleted successfully.',
        ]);
    }
}

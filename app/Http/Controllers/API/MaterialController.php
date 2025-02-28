<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;


use App\Models\Material;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\MaterialResource;

class MaterialController extends BaseController
{

    public function index()
    {
        $materials = Material::all();
        return MaterialResource::collection($materials);
    }

    public function listNames()
    {
        $materials = Material::select('id', 'name')->get();
        return response()->json($materials);
    }

    /**
     * Store a newly created material.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:materials,name|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $material = Material::create($request->only(['name']));

        return response()->json([
            'success' => true,
            'message' => 'Material created successfully.',
            'data' => new MaterialResource($material),
        ], 201);
    }

    /**
     * Display the specified Material.
     */
    public function show(Material $material)
    {
        return new MaterialResource($material);
    }

    /**
     * Update the specified material.
     */
    public function update(Request $request, Material $material)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:materials,name,' . $material->id . '|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $material->update($request->only(['name']));

        return response()->json([
            'success' => true,
            'message' => 'Material updated successfully.',
            'data' => new MaterialResource($material),
        ]);
    }

    /**
     * Remove the specified material.
     */
    public function destroy(Material $material)
    {
        $material->delete();

        return response()->json([
            'success' => true,
            'message' => 'Material deleted successfully.',
        ]);
    }
}

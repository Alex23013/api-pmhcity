<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;


use App\Models\Metropole;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\MetropoleResource;

class MetropoleController extends BaseController
{

    public function index()
    {
        $categories = Metropole::with('cities')->get();
        return MetropoleResource::collection($categories);
    }

    public function listNames()
    {
        $categories = Metropole::select('id', 'name')->get();
        return response()->json($categories);
    }

    /**
     * Store a newly created metropole.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:metropoles,name|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $metropole = Metropole::create($request->only(['name']));

        return response()->json([
            'success' => true,
            'message' => 'Metropole created successfully.',
            'data' => new MetropoleResource($metropole),
        ], 201);
    }

    /**
     * Display the specified Metropole.
     */
    public function show(Metropole $metropole)
    {
        return new MetropoleResource($metropole->load('cities'));
    }

    /**
     * Update the specified metropole.
     */
    public function update(Request $request, Metropole $metropole)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:metropoles,name,' . $metropole->id . '|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $metropole->update($request->only(['name']));

        return response()->json([
            'success' => true,
            'message' => 'Metropole updated successfully.',
            'data' => new MetropoleResource($metropole),
        ]);
    }

    /**
     * Remove the specified metropole.
     */
    public function destroy(Metropole $metropole)
    {
        $metropole->delete();

        return response()->json([
            'success' => true,
            'message' => 'Metropole deleted successfully.',
        ]);
    }
}

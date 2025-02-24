<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use App\Http\Resources\CityResource;
use Illuminate\Http\JsonResponse;

class CityController extends Controller
{
    /**
     * Display a list of cities.
     */
    public function index(): JsonResponse
    {
        $cities = City::with('metropole')->get();
        return response()->json(CityResource::collection($cities));
    }

    public function getCitiesByMetropole($metropole_id): JsonResponse
    {
        $cities = City::where('metropole_id', $metropole_id)->get();

        if ($cities->isEmpty()) {
            return response()->json(['error' => 'No cities found for this metropole.'], 404);
        }

        return response()->json(CityResource::collection($cities));
    }

    /**
     * Store a new city.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|unique:cities,name',
            'metropole_id' => 'required|exists:metropoles,id',
        ]);

        $city = City::create($request->all());

        return response()->json(new CityResource($city), 201);
    }

    /**
     * Display a single city.
     */
    public function show($id): JsonResponse
    {
        $city = City::with('metropole')->find($id);

        if (!$city) {
            return response()->json(['error' => 'City not found.'], 404);
        }

        return response()->json(new CityResource($city));
    }

    /**
     * Update a city.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $city = City::find($id);

        if (!$city) {
            return response()->json(['error' => 'City not found.'], 404);
        }

        $request->validate([
            'name' => 'sometimes|string|unique:cities,name,' . $id,
            'metropole_id' => 'sometimes|exists:metropoles,id',
        ]);

        $city->update($request->all());

        return response()->json(new CityResource($city));
    }

    /**
     * Delete a city.
     */
    public function destroy($id): JsonResponse
    {
        $city = City::find($id);

        if (!$city) {
            return response()->json(['error' => 'City not found.'], 404);
        }

        $city->delete();

        return response()->json(['message' => 'City deleted successfully.']);
    }
}

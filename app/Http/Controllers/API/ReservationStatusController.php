<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;


use App\Models\ReservationStatus;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ReservationStatusResource;

class ReservationStatusController extends BaseController
{

    public function index()
    {
        $reservation_statuses = ReservationStatus::all();
        return ReservationStatusResource::collection($reservation_statuses);
    }

    public function listNames()
    {
        $reservation_statuses = ReservationStatus::select('id', 'name', 'display_name')->get();
        return response()->json($reservation_statuses);
    }

    /**
     * Store a newly created brand.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:reservation_statuses,name|max:255',
            'display_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $brand = ReservationStatus::create($request->only(['name']));

        return response()->json([
            'success' => true,
            'message' => 'ReservationStatus created successfully.',
            'data' => new ReservationStatusResource($brand),
        ], 201);
    }

    /**
     * Display the specified ReservationStatus.
     */
    public function show(ReservationStatus $brand)
    {
        return new ReservationStatusResource($brand);
    }

    /**
     * Update the specified brand.
     */
    public function update(Request $request, ReservationStatus $brand)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:reservation_statuses,name,' . $brand->id . '|max:255',
            'display_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $brand->update($request->only(['name','display_name']));

        return response()->json([
            'success' => true,
            'message' => 'ReservationStatus updated successfully.',
            'data' => new ReservationStatusResource($brand),
        ]);
    }

    /**
     * Remove the specified brand.
     */
    public function destroy(ReservationStatus $brand)
    {
        $brand->delete();

        return response()->json([
            'success' => true,
            'message' => 'ReservationStatus deleted successfully.',
        ]);
    }
}

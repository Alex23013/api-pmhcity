<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use App\Http\Resources\ApplicationResource;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $applications = Application::all();
        return ApplicationResource::collection($applications);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'             => 'required|string|max:255',
            'lastname'         => 'required|string|max:255',
            'phone'            => 'required|string|max:20',
            'email'            => 'required|string|email|max:255',
            'store_name'       => 'required|string|max:255',
            'store_location'   => 'required|string|max:255',
            'store_url'        => 'nullable|max:255',
            'store_description'=> 'nullable|string',
            'admin_notes'      => 'nullable|string',
            'category'         => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $application = Application::create($validator->validated());

        return new ApplicationResource($application);
    }

    /**
     * Display the specified resource.
     */
    public function show(Application $application)
    {
        return new ApplicationResource($application);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Application $application)
    {
        $validator = Validator::make($request->all(), [
            'name'             => 'sometimes|string|max:255',
            'lastname'         => 'sometimes|string|max:255',
            'phone'            => 'sometimes|string|max:20',
            'email'            => 'sometimes|string|email|max:255|unique:applications,email,' . $application->id,
            'store_name'       => 'sometimes|string|max:255',
            'store_location'   => 'sometimes|string|max:255',
            'store_url'        => 'nullable|url|max:255',
            'store_description'=> 'nullable|string',
            'admin_notes'      => 'nullable|string',
            'category'         => 'sometimes|string|max:255',
            'status'           => 'in:new,contacted,in_progress,onboarded,rejected,completed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $application->update($validator->validated());

        return new ApplicationResource($application);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Application $application)
    {
        $application->delete();

        return response()->json([
            'success' => true,
            'message' => 'Application deleted successfully.',
        ]);
    }
}

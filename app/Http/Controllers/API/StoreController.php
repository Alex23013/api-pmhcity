<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Store;
use Illuminate\Support\Facades\Validator;

class StoreController extends BaseController
{
    public function listStores()
    {
        $sellers = User::whereIn('role_id', [2, 3])->get();

        $stores = [];
        foreach ($sellers as $seller) {
            $store = $seller->store;
            if ($store) {
                $stores[] = [
                    "store" =>[
                        "id" => $store->id,
                        "name" => $store->name,
                        "logo" => $store->logo,
                        "subtitle"=> "store description",
                    ],
                ];
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Stores retrieved successfully.',
            'data' => $stores
        ], 200);
    }

    public function listProductsByStore($storeId)
    {
        $store = Store::find($storeId);
        if (!$store) {
            return response()->json([
                'status' => false,
                'message' => 'Store not found.'
            ], 404);
        }

        $user = $store->user;

        $products = $user->products()->with(['brand', 'material', 'status_product', 'category', 'subcategory'])->get();

        return response()->json([
            'status' => true,
            'message' => 'Products retrieved successfully.',
            'data' => [
                'products' => $products,
                'store' => [
                        "logo" => $user->profile_picture,
                        "name" => $store->name,
                        "phone" => $user->phone,
                        "city" => $user->city->name,
                        "metropole" => $user->city->metropole->name,
                        "banner" => $store->logo,
                        ]
                ]
        ], 200);
    }

    public function verifyStore(Request $request)
    {
        $request->validate([
            'siret' => 'required|string',
            'company_name' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $store = $user->store; // Ensure the user has a store

        if (!$store) {
            return response()->json([
                'status' => false,
                'message' => 'Store not found for this user.'
            ], 404);
        }

        // Update store details
        $store->update([
            'siret' => $request->siret,
            'name' => $request->company_name,
        ]);

        $user = User::find($user->id); 
        $user->update([
            'profile_status' => 'pending revision'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Store data sent successfully, profile under review.',
            'data' => [
                'store' => $store,
                'user' => $user
            ]
        ], 200);
    }

    public function markAsVerifiedStore(Request $request, $storeId)
    {
        $store = Store::find($storeId);

        if (!$store) {
            return response()->json([
                'status' => false,
                'message' => 'Store not found.'
            ], 404);
        }

        $store->update(['is_verified' => true]);

        return response()->json([
            'status' => true,
            'message' => 'Store verified successfully.',
            'data' => $store
        ], 200);
    }

    public function update(Request $request, $id)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'city_id' => 'required',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        $store = Store::findOrFail($id);
        $store->update($request->only(['name']));

        $user = $store->user;
        $user->update($request->only(['city_id']));

        if ($request->hasFile('logo')) {
            $imagePath = $request->file('logo')->store('stores', 'public');
            $store->logo = asset('storage/' . $imagePath);
            $store->save();
        }

        if ($request->hasFile('banner')) {
            $imagePath = $request->file('banner')->store('stores', 'public');
            $store->banner = asset('storage/' . $imagePath);
            $store->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'Store updated successfully',
            'data' => $store
        ], 200);
    }
}

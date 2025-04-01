<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Store;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ProductResource;

class StoreController extends BaseController
{
    public function listStores()
    {
        $sellers = User::whereIn('role_id', [2, 3])->get();

        $stores = [];
        foreach ($sellers as $seller) {
            // Eager load related models
            $products = $seller->products()->with(['brand', 'material', 'status_product', 'category', 'subcategory'])->get();

            // Get unique category IDs from the user's products
            $categoryIds = $products->pluck('category_id')->unique()->toArray();

            // Randomly select 3 category IDs
            $randomCategoryIds = collect($categoryIds)->random(min(3, count($categoryIds)));

            // Retrieve the category details for the selected category IDs
            $category_names = Category::whereIn('id', $randomCategoryIds)->pluck('name')->toArray();
            $category_names_string = implode(', ', $category_names);

            $store = $seller->store;
            if ($store) {
                $stores[] = [
                    "store" =>[
                        "id" => $store->id,
                        "name" => $store->name,
                        "description" => $store->description,
                        "logo" => $store->logo,
                        "subtitle"=> $category_names_string,
                    ],
                ];
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Stores retrieved successfully.',
            'data' => [
                "stores" => $stores
            ]
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

        $products = $user->products()->with(['brand', 'material', 'status_product', 'category', 'subcategory', 'photoProducts'])->get();
        
        return response()->json([
            'status' => true,
            'message' => 'Products retrieved successfully.',
            'data' => [
                'products' => ProductResource::collection($products),
                'store' => [
                        "logo" => $store->logo,
                        "name" => $store->name,
                        "phone" => $user->phone,
                        "city" => $user->city->name,
                        "metropole" => $user->city->metropole->name,
                        "banner" => $store->banner,
                        ]
                ]
        ], 200);
    }

    public function verifyStore(Request $request)
    {
        $request->validate([
            'siret' => 'required|string'
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
            'siret' => $request->siret
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

<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json([
            'status' => true,
            'message' => 'Users retrieved successfully',
            'data' => $users
        ], 200);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json([
            'status' => true,
            'message' => 'User found successfully',
            'data' => $user
        ], 200);
    }

    public function profile()
    {
        $user = Auth::user();
        $user->role;
        $user->city->metropole;

        $monthly_earnings = 0;
        $require_verification = false;

        if (in_array($user->role_id, [2, 3])) {
            $user->store;
            $monthly_earnings = 10.00;
            if($monthly_earnings > 300){
                $require_verification = true;
            }else{
                $require_verification = false;
            }
        }
        
        return response()->json([
            'status' => true,
            'message' => 'User profile retrieved successfully',
            'data' => [
                'user' => $user,
                'monthly_earnings' => $monthly_earnings,
                'require_verification' => $require_verification
            ]
        ], 200);
    }

    public function store(Request $request) //not used: evalute for deprecation
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create($request->all());
        return response()->json([
            'status' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], 201);
    }

    public function update(Request $request, $id)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        $user = User::findOrFail($id);
        $user->update($request->only(['name', 'lastname', 'email']));

        if ($request->hasFile('profile_picture')) {
            $imagePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = asset('storage/' . $imagePath);
            $user->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'User updated successfully',
            'data' => $user
        ], 200);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        
        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully'
        ], 204);
    }
}

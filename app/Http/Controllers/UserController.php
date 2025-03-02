<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        $user->store;
        $user->role;
        $user->city->metropole;
        $monthly_earnings = 10.00;
        if($monthly_earnings > 300){
            $require_verification = true;
        }else{
            $require_verification = false;
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
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        $user = User::findOrFail($id);
        $user->update($request->all());

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

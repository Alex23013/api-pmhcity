<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\ForgotPasswordTokenMail;
use Illuminate\Support\Facades\Mail;

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
            $monthly_earnings = 10.00; // TODO: calculate monthly earnings
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

    public function sendResetToken(Request $request)
    {
        // Validate email
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        // Generate token and expiration
        $token = Str::random(64);
        $expiresAt = Carbon::now()->addMinutes(10);

        // Update user
        $user->update([
            'forget_password_token' => $token,
            'fpt_expires_at' => $expiresAt,
        ]);

        Mail::to($user->email)->send(new ForgotPasswordTokenMail($user, $token));
        
        return response()->json([
            'success' => true,
            'message' => 'Reset token generated.'
        ]);
    }

    public function resetPassword(Request $request)
    {
        // Validate token and password
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('forget_password_token', $request->token)->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid token.'], 404);
        }
        
        if (!$user->fpt_expires_at || Carbon::now('UTC')->greaterThan(Carbon::parse($user->fpt_expires_at))) {
            return response()->json(['message' => 'Token has expired.'], 403);
        }
        $user->password = bcrypt($request->password);
        $user->forget_password_token = null;
        $user->fpt_expires_at = null;
        $user->save();

        return response()->json(['message' => 'Password updated successfully.']);
    }

}

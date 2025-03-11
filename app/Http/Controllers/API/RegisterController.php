<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Models\PhoneToken;
use Illuminate\Support\Facades\Validator;

class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'phone' => 'required|string|max:12',
            'role_id' => 'required|exists:roles,id'
        ]);


   

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $phoneToken = PhoneToken::where('phone_number', $request->phone)
            ->where('is_verified', true)
            ->first();

        if (!$phoneToken) {
            return $this->sendError('Phone number is not verified.', ['phone' => 'The provided phone number has not been verified.']);
        }
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        if (in_array($user->role_id, [2, 3])) { // only create a store for sellers
            Store::create(['user_id' => $user->id]);
        }        
        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['name'] =  $user->name;
   
        return $this->sendResponse($success, 'User register successfully.');
    }
   
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request): JsonResponse
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')->plainTextToken; 
            $success['name'] =  $user->name;
   
            return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }
    /*public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Ensure the session is refreshed
            
            return response()->json([
                'message' => 'User login successful',
                'user' => Auth::user(),
            ]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }*/
}

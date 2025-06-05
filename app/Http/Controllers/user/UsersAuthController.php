<?php

namespace App\Http\Controllers\user;
use App\Http\Controllers\Controller;

use App\Http\Requests\LoginAdminRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\Admin;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersAuthController extends Controller
{  
    use HttpResponses;  
    // User login
    public function login(LoginUserRequest $request)
    {
        $user = User::where('phone', $request->phone)->first();
    
        // Check if user exists and verify password manually
        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->error('', 'Invalid credentials', 401);
        }
    
        return $this->success([
            'user' => $user,
            'token' => $user->createToken('Access Token for ' . $user->name)->plainTextToken,
        ]);
    }
    

    // Register new user (only if no user exists)
    public function register(StoreUserRequest $request)
    {

        $user = User::create($request->validated());

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('user-token')->plainTextToken,
        ], 'User registered successfully!', 201);
    }

    // User logout
    public function logout(Request $request)
    {
        $user = Auth::guard('user')->user(); // Use the user guard

        if (!$user) {
            return response()->json([
                'status' => 'Error has occurred...',
                'message' => 'Unauthorized access. Users only.',
                'data' => ''
            ], 403);
        }

        // Revoke current access token (using user guard)
        $user->currentAccessToken()?->delete(); // Safe way to delete token

        return response()->json([
            'status' => 'Success',
            'message' => 'Logged out successfully',
            'data' => ''
        ]);
    }

}

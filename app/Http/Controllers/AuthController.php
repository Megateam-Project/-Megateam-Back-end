<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct()
    {
        Auth::shouldUse('api');
    }

    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'min:8'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'avatar' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 405);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone'=>$request->phone,
            'password' => bcrypt($request->password),
            'avatar'=>$request->avatar,
            'role'=>$request->role,
            'create_by' => $request->create_by,
        ]);

        return response()->json([
            'message' => 'Successfully created user!',
            'user' => $user
        ], 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }

        $credentials = $request->only('email', 'password');
        if ($token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'response' => 'success',
                'result' => [
                    'token' => $token,
                ],
            ]);
        }

        return response()->json([
            'response' => 'error',
            'message' => 'invalid_email_or_password',
        ], 400);
    }

    // public function login(Request $request)
    // {
    //     $credentials = request(['email', 'password']);

    //     if (! $token = auth()->attempt($credentials)) {
    //         return response()->json(['error' => 'Unauthorized'], 401);
    //     }

    //     return $this->respondWithToken($token);
    // }


    public function logout()
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        Auth::logout();

        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
    }

    public function refresh()
    {
        try {
            $token = JWTAuth::parseToken()->refresh();
            return response()->json(['token' => $token], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    public function user(Request $request)
    {
        if (Auth::check()) {
            return response()->json([
                'status' => true,
                'response' => Auth::user(),
            ], 200);
        } else {
            return response()->json([
                'status' => false,
            ], 401);
        }
    }
}

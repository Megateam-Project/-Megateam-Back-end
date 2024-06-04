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
    /**
     * @OA\POST(
     *     path="/api/signup",
     *     tags={"Authentication"},
     *     summary="Register User",
     *     description="Register New User",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string", example="Jhon Doe"),
     *              @OA\Property(property="email", type="string", example="jhondoe@example.com"),
     *              @OA\Property(property="phone", type="string", example="0437846324"),
     *              @OA\Property(property="password", type="string", example="12345678"),
     *              @OA\Property(property="password_confirmation", type="string", example="12345678"),
     *              @OA\Property(property="avatar", type="string"),
     *              @OA\Property(property="role", type="string", example="user"),
     *              @OA\Property(property="create_by", type="string", example="user")
     *          ),
     *      ),
     *      @OA\Response(response=200, description="Register New User Data" ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found")
     * )
     */
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'min:8'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            // 'avatar' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 405);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            // 'avatar' => $request->avatar,
            'role' => $request->role,
            'create_by' => $request->create_by,
        ]);

        return response()->json([
            'message' => 'Successfully created user!',
            'user' => $user
        ], 200);
    }
    /**
     * @OA\POST(
     *     path="/api/login",
     *     tags={"Authentication"},
     *     summary="Login",
     *     description="Login",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="email", type="string", example="admin@example.com"),
     *              @OA\Property(property="password", type="string", example="123456")
     *          ),
     *      ),
     *      @OA\Response(response=200, description="Login"),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found")
     * )
     */
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

    /**
 * @OA\POST(
 *     path="/api/logout",
 *     tags={"Authentication"},
 *     summary="Logout",
 *     description="Logout",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="token", type="string", example="your_access_token_here")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Logout"),
 *     @OA\Response(response=400, description="Bad request"),
 *     @OA\Response(response=404, description="Resource Not Found"),
 * )
 */

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
    public function profile()
    {
        return response()->json(auth('api')->user());
    }
    // protected function respondWithToken($token)
    // {
    //     return response()->json([
    //         'access_token' => $token,
    //         'token_type' => 'bearer',
    //         'expires_in' => auth()->factory()->getTTL() * 60
    //     ]);
    // }
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

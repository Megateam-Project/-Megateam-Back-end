<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\GET(
     *     path="/api/users",
     *     tags={"User"},
     *     summary="Get User List",
     *     description="Get User List as Array",
     *     operationId="indexv3",
     *     security={{"bearer":{}}},
     *     @OA\Response(response=200,description="Get User List as Array"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function __construct()
    {
        $this->user = new User;
    }
    private $user;
    public function index()
    {
        $users = User::all();
        return response()->json($users, 200);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
    }
    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *     path="/api/users",
     *     summary="Create a new user",
     *     description="Create a new user",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "phone", "avatar", "password", "role", "create_by"},
     *             @OA\Property(property="name", type="string", example="Khanh Nhi"),
     *             @OA\Property(property="email", type="string", example="nhi@gmail.com"),
     *             @OA\Property(property="phone", type="string", example="0374943992"),
     *             @OA\Property(property="avatar", type="string", example="https://s.net.vn/a5IG"),
     *             @OA\Property(property="password", type="string", example="12345678"),
     *             @OA\Property(property="role", type="string", example="admin/user"),
     *             @OA\Property(property="create_by", type="string", example="Admin"),
     *         )
     *     ),
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200, description="Create New User"),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validate = [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'phone' => 'required|string',
            'avatar' => 'required|string',
            'role' => 'required|string',
            'password' => 'required|string',
            'create_by' => 'required|string'
        ];
        $validator = Validator::make($request->all(), $validate);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $dataInsert = $validator->validated();
        try {
            $insertUser = User::create($dataInsert);
            return response()->json([
                'message' => 'success',
                'data' => $insertUser
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Get a specific user",
     *     tags={"User"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Show User Detail"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function show(string $id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json($user, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error', 'error' => $e->getMessage()], 500);
        }
    }
    /**
     * Update the specified resource in storage.
     */
    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     summary="Update a specific user",
     *     tags={"User"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *             @OA\Property(property="name", type="string", example="Khanh Nhi"),
     *             @OA\Property(property="email", type="string", example="nhi@gmail.com"),
     *             @OA\Property(property="phone", type="string", example="0374943992"),
     *             @OA\Property(property="avatar", type="string", example="https://s.net.vn/a5IG"),
     *             @OA\Property(property="password", type="string", example="12345678"),
     *             @OA\Property(property="role", type="string", example="admin/user"),
     *             @OA\Property(property="update_by", type="string", example="Admin"),
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Update User"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function update(Request $request, string $id)
    {
        try {
            $validate = Validator::make($request->all(),[
                'name' => 'required|string',
                'email' => 'required|string',
                'phone' => 'required|string',
                // 'avatar' => 'required|string',
                // 'update_by' => 'required|string'
            ]);
            if($validate->fails()){
                return response()->json(['error'=>$validate->errors()],404);
            }

            $user = User::findOrFail($id);
            $user->update($request->all());
            return response()->json(['message' => 'Updated user successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error', 'error' => $e->getMessage()], 500);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Delete a specific user",
     *     tags={"User"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Delete user"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(['message' => 'Deleted user successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error', 'error' => $e->getMessage()], 500);
        }
    }
}

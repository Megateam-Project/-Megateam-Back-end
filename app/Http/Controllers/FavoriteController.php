<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->favorite = new Favorite;
    }
    private $favorite;
    public function index()
    {
        $favorite = Favorite::all();
        return response()->json($favorite, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *     path="/api/favorites",
     *     summary="Create a new favorite",
     *     description="Create a new favorite",
     *     tags={"Favorite"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={""user_id", "room_id", "create_by"},
     *             @OA\Property(property="user_id", type="integer", example="2"),
     *             @OA\Property(property="room_id", type="integer", example="2"),
     *             @OA\Property(property="create_by", type="string", example="Admin"),
     *         )
     *     ),
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200, description="Create New Favorite"),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validate = [
            'user_id' => 'required|integer',
            'room_id' => 'required|integer',
            'create_by' => 'required|string',
        ];
        $validator = Validator::make($request->all(), $validate);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $dataInsert = $validator->validated();
        try {
            $addWishlist = Favorite::create($dataInsert);
            return response()->json([
                'message' => 'success',
                'data' => $addWishlist
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error', 'error' => $e->getMessage()], 500);
        }
    }
   /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/api/favorites/{id}",
     *     summary="Get a specific favorite",
     *     tags={"Favorite"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Favorite ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Show Favorite Detail"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function show(string $id)
    {
        $favorite = Favorite::with('user', 'room')->find($id);
        if ($favorite) {
            return response()->json(['Favorite' => $favorite], 200);
        } else {
            return response()->json(['message' => 'Favorite not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * @OA\Delete(
     *     path="/api/favotites/{id}",
     *     summary="Delete a specific favorite",
     *     tags={"Favorite"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Favorite ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Delete favorite"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function destroy(string $id)
    {
        try {
            $favorite = Favorite::findOrFail($id);
            $favorite->delete();
            return response()->json(['message' => 'Deleted item successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error', 'error' => $e->getMessage()], 500);
        }
    }
}

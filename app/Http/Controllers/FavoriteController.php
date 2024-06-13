<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;
class FavoriteController extends Controller
{
    public function getFavoriteRooms($userId)
    {
        $wishlist = Favorite::where('user_id', $userId)->with('room')->get();
        $rooms = $wishlist->map(function ($item) {
            return $item->room;
        });
        return response()->json($rooms);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $favorites = Favorite::all();
        return response()->json($favorites, 200);
    }

    /**
     * Store a newly created resource in storage.
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
            $insertFavorite = Favorite::create($dataInsert);
            return response()->json([
                'message' => 'success',
                'data' => $insertFavorite
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
    public function show(string $id)
    {
        //
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
    public function destroy(string $id)
    {
        //
    }
}

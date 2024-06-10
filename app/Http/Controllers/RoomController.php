<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\GET(
     *     path="/api/rooms",
     *     tags={"Room"},
     *     summary="Get Room List",
     *     description="Get Room List as Array",
     *     operationId="indexV2",
     *     security={{"bearer":{}}},
     *     @OA\Response(response=200,description="Get Room List as Array"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    protected $rooms;
    public function __construct()
    {
        $this->rooms = new Room();
    }
    public function index()
    {
        $rooms = Room::all();
        return response()->json($rooms);
    }

    /**
 * Display the specified resource.
 */
/**
 * @OA\Get(
 *     path="/api/rooms/{id}",
 *     summary="Get a specific Room by ID",
 *     tags={"Room"},
 *     operationId="show",
 *     security={{"bearer":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Room ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Room found",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example=1, description="Room ID"),
 *             @OA\Property(property="name", type="string", maxLength=50, example="Deluxe Room", description="Name of the room"),
 *             @OA\Property(property="type", type="string", maxLength=50, example="Double", description="Type of the room"),
 *             @OA\Property(property="price", type="number", format="float", example=150.00, description="Price of the room"),
 *             @OA\Property(property="description", type="string", maxLength=500, nullable=true, description="Description of the room"),
 *             @OA\Property(property="image", type="string", maxLength=15000, nullable=true, description="Image URL of the room"),
 *             @OA\Property(property="convenient", type="string", maxLength=200, nullable=true, description="Convenient features of the room"),
 *             @OA\Property(property="number", type="integer", example=10, description="Number of available rooms"),
 *             @OA\Property(property="discount", type="number", format="float", example=10.00, description="Discount percentage for the room"),
 *             @OA\Property(property="created_at", type="string", format="date-time", description="Room creation date"),
 *             @OA\Property(property="updated_at", type="string", format="date-time", description="Room last update date"),
 *         )
 *     ),
 *     @OA\Response(response=404, description="Room not found"),
 * )
 */
public function show($id)
{
    $room = Room::findOrFail($id);
    return response()->json($room);
}

    /**
 * @OA\Post(
 *     path="/api/rooms",
 *     summary="Create a new Room",
 *     tags={"Room"},
 *     operationId="store",
 *     security={{"bearer":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Room data",
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="name",
 *                     type="string",
 *                     maxLength=50,
 *                     example="Deluxe Room",
 *                     description="Name of the room"
 *                 ),
 *                 @OA\Property(
 *                     property="type",
 *                     type="string",
 *                     maxLength=50,
 *                     example="Double",
 *                     description="Type of the room"
 *                 ),
 *                 @OA\Property(
 *                     property="price",
 *                     type="number",
 *                     format="float",
 *                     example="150.00",
 *                     description="Price of the room"
 *                 ),
 *                 @OA\Property(
 *                     property="description",
 *                     type="string",
 *                     maxLength=500,
 *                     nullable=true,
 *                     description="Description of the room"
 *                 ),
 *                 @OA\Property(
 *                     property="image",
 *                     type="string",
 *                     format="binary",
 *                     description="Image of the room"
 *                 ),
 *                 @OA\Property(
 *                     property="convenient",
 *                     type="string",
 *                     maxLength=200,
 *                     nullable=true,
 *                     description="Convenient features of the room"
 *                 ),
 *                 @OA\Property(
 *                     property="number",
 *                     type="integer",
 *                     example="10",
 *                     description="Number of available rooms"
 *                 ),
 *                 @OA\Property(
 *                     property="discount",
 *                     type="number",
 *                     format="float",
 *                     example="10.00",
 *                     description="Discount percentage for the room"
 *                 ),
 *                 @OA\Property(
 *                     property="create_by",
 *                     type="string",
 *                     maxLength=50,
 *                     example="Admin",
 *                     description="Name of the creator"
 *                 ),
 *             )
 *         )
 *     ),
 *     @OA\Response(response=200, description="Room created successfully"),
 *     @OA\Response(response=400, description="Bad request"),
 *     @OA\Response(response=500, description="Internal server error"),
 * )
 */

   public function store(Request $request)
{
    $validate = [
        'name' => 'required|string|max:50',
        'type' => 'required|string|max:50',
        'price' => 'required|numeric',
        'description' => 'nullable|string|max:500',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        'convenient' => 'nullable|string|max:200',
        'number' => 'required|integer',
        'discount' => 'required|numeric',
        'create_by' => 'required|string|max:50',
    ];

    $validator = Validator::make($request->all(), $validate);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
    }

    $dataInsert = $validator->validated();

    try {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/images', $imageName); // Lưu hình ảnh vào thư mục lưu trữ
            $dataInsert['image'] = 'storage/images/' . $imageName; // Cập nhật đường dẫn hình ảnh trong dataInsert
        }

        $room = Room::create($dataInsert);
        return response()->json(['message' => 'success', 'data' => $room], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'error', 'error' => $e->getMessage()], 500);
    }
}

   /**
     * Update the specified resource in storage.
     * 
     * @OA\Put(
     *     path="/api/rooms/{id}",
     *     summary="Update a specific Room",
     *     tags={"Room"},
     *     operationId="update",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Room ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Room data",
     *         @OA\JsonContent(
     *             required={"name", "type", "price", "description", "image", "convenient", "number", "discount", "update_by"},
     *             @OA\Property(property="name", type="string", example="Deluxe Room"),
     *             @OA\Property(property="type", type="string", example="Double"),
     *             @OA\Property(property="price", type="number", format="float", example="150.00"),
     *             @OA\Property(property="description", type="string", maxLength=500, nullable=true, description="Description of the room"),
     *             @OA\Property(property="image", type="string", maxLength=15000, nullable=true, description="Image URL of the room"),
     *             @OA\Property(property="convenient", type="string", maxLength=200, nullable=true, description="Convenient features of the room"),
     *             @OA\Property(property="number", type="integer", example="10", description="Number of available rooms"),
     *             @OA\Property(property="discount", type="number", format="float", example="10.00", description="Discount percentage for the room"),
     *             @OA\Property(property="update_by", type="string", maxLength=50, example="Admin", description="Name of the updater"),
     *         )
     *     ),
     *     @OA\Response(response=200, description="Room updated successfully"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     *     @OA\Response(response=500, description="Internal server error"),
     * )
     */
    public function update(Request $request, $id)
{
    $validate = [
        'name' => 'required|string|max:50',
        'type' => 'required|string|max:50',
        'price' => 'required|numeric',
        'description' => 'nullable|string|max:500',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'convenient' => 'nullable|string|max:200',
        'number' => 'required|integer',
        'discount' => 'required|numeric',
        'update_by' => 'required|string|max:50',
    ];

    $validator = Validator::make($request->all(), $validate);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
    }

    $dataUpdate = $validator->validated();

    try {
        $room = Room::findOrFail($id);
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($room->image) {
                Storage::delete(str_replace('storage/', 'public/', $room->image));
            }
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/images', $imageName);
            $dataUpdate['image'] = 'storage/images/' . $imageName;
        }
        $room->update($dataUpdate);
        return response()->json(['message' => 'success', 'data' => $room], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'error', 'error' => $e->getMessage()], 500);
    }
}

    /**
 * Remove the specified resource from storage.
 */
/**
 * @OA\Delete(
 *     path="/api/rooms/{id}",
 *     summary="Delete a specific Room",
 *     tags={"Room"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Room ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     security={{"bearer":{}}},
 *     @OA\Response(response=200, description="Room deleted successfully"),
 *     @OA\Response(response=400, description="Bad request"),
 *     @OA\Response(response=404, description="Resource Not Found"),
 * )
 */

    public function destroy($id)
    {
        try {
            $room = Room::findOrFail($id);
            $room->update(['delete_by' => 'Admin']);
            $room->delete();
            return response()->json(['message' => 'Room deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error', 'error' => $e->getMessage()], 500);
        }
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('searchTerm');
        $searchTerm = '%' . $searchTerm . '%';
        $checkInDate = $request->input('check_in_date');
        $checkOutDate = $request->input('check_out_date');
    
        $room = Room::where('type', 'LIKE', $searchTerm)
                     ->with(['booking' => function ($query) use ($checkInDate, $checkOutDate) {
                         $query->where('check_in_date', '>=', $checkInDate)
                               ->where('check_out_date', '<=', $checkOutDate);
                     }])
                     ->get();
    
        $result = [];
        foreach ($room as $r) {
            if ($r->booking->isEmpty()) {
                $roomData = [
                    'id' => $r->id,
                    'name'=>$r->name,
                    'type' => $r->type,
                    'description'=>$r->description,
                    'price'=>$r->price,
                    'image'=>$r->image,
                    'convenient'=>$r->convenient,
                    'number'=>$r->number,
                    'discount'=>$r->discount,
                    'deleted_at' => $r->deleted_at,
                    'check_in_date' => optional($r->booking->first())->check_in_date,
                    'check_out_date' => optional($r->booking->first())->check_out_date
                ];
                $result[] = $roomData;
            }
        }
    
        if (count($result) === 0) {
            return response()->json([
                'message' => 'No rooms found matching the search term.'
            ], 200);
        } else {
            return response()->json([
                'rooms' => $result
            ], 200);
        }
    }
}

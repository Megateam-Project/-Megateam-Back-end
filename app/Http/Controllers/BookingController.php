<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\GET(
     *     path="/api/bookings",
     *     tags={"Booking"},
     *     summary="Get Booking List",
     *     description="Get Booking List as Array",
     *     operationId="index",
     *     security={{"bearer":{}}},
     *     @OA\Response(response=200,description="Get Booking List as Array"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function __construct()
    {
        $this->bookings = new Booking;
    }
    private $bookings;
    public function index()
    {
        $listBooking = $this->bookings->getAllBooking();
        return response()->json($listBooking);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *     path="/api/bookings",
     *     summary="Create a new booking",
     *     description="Create a new booking",
     *     tags={"Booking"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "room_id", "check_in_date", "check_out_date", "create_by"},
     *             @OA\Property(property="user_id", type="integer", example="1"),
     *             @OA\Property(property="room_id", type="integer", example="1"),
     *             @OA\Property(property="check_in_date", type="string", example="2024-05-02 08:47:14"),
     *             @OA\Property(property="check_out_date", type="string", example="2024-05-04 08:47:14"),
     *             @OA\Property(property="create_by", type="string", example="Admin"),
     *         )
     *     ),
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200, description="Create New Booking"),
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
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'create_by' => 'required|string',
        ];
        $validator = Validator::make($request->all(), $validate);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $dataInsert = $validator->validated();
        try {
            $insertBooking = Booking::create($dataInsert);
            return response()->json([
                'message' => 'success',
                'data' => $insertBooking
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
     *     path="/api/bookings/{id}",
     *     summary="Get a specific booking",
     *     tags={"Booking"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Booking ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Show Booking Detail"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function show(string $id)
    {
        $booking = Booking::with('user', 'room')->find($id);
        if ($booking) {
            return response()->json(['booking' => $booking], 200);
        } else {
            return response()->json(['message' => 'Booking not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * @OA\Put(
     *     path="/api/bookings/{id}",
     *     summary="Update a specific booking",
     *     tags={"Booking"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Booking ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                  @OA\Property(property="user_id", type="integer", example="1"),
     *                   @OA\Property(property="room_id", type="integer", example="1"),
     *                  @OA\Property(property="check_in_date", type="string", example="2024-05-02 08:47:14"),
     *                  @OA\Property(property="check_out_date", type="string", example="2024-05-04 08:47:14"),
     *                  @OA\Property(property="update_by", type="string", example="Admin"),
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Update Booking"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'user_id' => 'required|integer',
            'room_id' => 'required|integer',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'update_by' => 'required|string',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $dataUpdate = $validator->validated();
        try {
            $booking = Booking::find($id);
            $booking->update($dataUpdate);
            return response()->json([
                'message' => 'success',
                'data' => $booking
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * @OA\Delete(
     *     path="/api/bookings/{id}",
     *     summary="Delete a specific booking",
     *     tags={"Booking"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Booking ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Delete booking"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function destroy(string $id)
    {
        $booking = Booking::find($id);

        if ($booking) {
            $booking->update(['delete_by' => 'Admin']);
            $booking->delete();
            return response()->json(['message' => 'Booking deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'Booking not found'], 404);
        }
    }
    /**
     * @OA\Patch(
     *     path="/api/bookings/{id}/restore",
     *     summary="Restore a specific booking",
     *     tags={"Booking"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Booking ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Booking restored successfully"),
     *     @OA\Response(response=400, description="Booking is not deleted, cannot be restored"),
     *     @OA\Response(response=404, description="Booking not found"),
     *     security={{"bearerAuth":{}}}
     * )
     */

    public function restore(string $id)
    {
        $booking = Booking::withTrashed()->find($id);
        if ($booking) {
            if ($booking->trashed()) {
                $booking->restore();
                return response()->json(['message' => 'Booking restored successfully'], 200);
            } else {
                return response()->json(['message' => 'Booking is not deleted'], 400);
            }
        } else {
            return response()->json(['message' => 'Booking not found'], 404);
        }
    }
}
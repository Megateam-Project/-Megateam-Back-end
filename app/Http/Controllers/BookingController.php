<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

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
    public function __construct(){
       $this-> bookings = new Booking; 
    }
    private $bookings; 
    public function index()
    {
        $listBooking = $this -> bookings -> getAllBooking();
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
    public function store(Request $request)
    {
        $dataInsert = [
            'user_id' => $request->input('user_id'),
            'room_id' => $request->input('room_id'),
            'check_in_date' => $request->input('check_in_date'),
            'check_out_date' => $request->input('check_out_date'),
            'create_by' => $request->input('create_by'),
        ];
        $insertBooking = Booking::create($dataInsert);
        if (!$insertBooking){
            return response()->json(['message'=>'error'],404);
        }
        return response()->json([
            'message'=>'success',
            'data' => $insertBooking],200);
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

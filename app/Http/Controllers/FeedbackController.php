<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{
     protected $feedbacks;

     public function __construct()
     {
         $this->feedbacks = new Feedback();
     }
      /**
     * Display a listing of the resource.
     */

     /**
     * @OA\Get(
     *     path="/api/feedbacks",
     *     summary="Get all feedbacks",
     *     tags={"Feedback"},
     *     description="Returns a list of all feedbacks.",
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="No feedbacks found")
     * )
     */
    public function index()
    {
        $feedbacks = Feedback::with('user', 'room')->get();
        return response()->json($feedbacks);
    }
   /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

   /**
     * @OA\Post(
     *     path="/api/feedbacks",
     *     summary="Create a new feedback",
     *     tags={"Feedback"},
     *     description="Create a new feedback for a specific room by a user.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"room_id", "user_id", "content", "create_by"},
     *             @OA\Property(property="room_id", type="integer", example="1"),
     *             @OA\Property(property="user_id", type="integer", example="1"),
     *             @OA\Property(property="content", type="string", maxLength=250, example="This is a feedback content"),
     *             @OA\Property(property="create_by", type="string", maxLength=50, example="User"),
     *             @OA\Property(property="update_by", type="string", maxLength=50, example="Admin"),
     *             @OA\Property(property="delete_by", type="string", maxLength=50, example="Admin"),
     *         )
     *     ),
     *     @OA\Response(response=201, description="Feedback created successfully"),
     *     @OA\Response(response=400, description="Invalid data provided")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_id' => 'required|integer',
            'user_id' => 'required|integer',
            'content' => 'required|string',
            'create_by' => 'nullable|string',
            'update_by' => 'nullable|string',
            'delete_by' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $feedback = Feedback::create($validator->validated());

        return response()->json(['message' => 'Feedback created successfully', 'data' => $feedback], 201);
    }

    /**
     * Display the specified resource.
     */

     /**
     * @OA\Get(
     *     path="/api/feedbacks/{id}",
     *     summary="Get a specific feedback",
     *     tags={"Feedback"},
     *     description="Returns a specific feedback.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the feedback",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Feedback not found")
     * )
     */
    public function show(string $id)
    {
        $feedback = Feedback::findOrFail($id);
        return response()->json($feedback);
    }

    /**
     * @OA\Get(
     *     path="/api/feedbacks/{room_id}",
     *     summary="Get feedbacks for a specific room",
     *     tags={"Feedback"},
     *     description="Retrieve all feedbacks associated with a specific room by its ID.",
     *     @OA\Parameter(
     *         name="room_id",
     *         in="path",
     *         description="ID of the room",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Show feedbacks for the room"),
     *     @OA\Response(response=404, description="Room not found")
     * )
     */
    public function getRoomFeedbacks(string $room_id)
    {
        $feedbacks = Feedback::where('room_id', $room_id)->with('user')->get();
        if ($feedbacks->isEmpty()) {
            return response()->json(['message' => 'No feedbacks found for the room'], 404);
        }
        return response()->json(['feedbacks' => $feedbacks], 200);
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

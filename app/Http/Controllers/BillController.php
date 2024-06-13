<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Bill;
use Illuminate\Http\Request;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $listBill = Bill::all();
        return response()->json($listBill);
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
        $validate = [
            'booking_id' => 'required|integer',
            'total_price' => 'required|integer',
            'date' => 'required|date',
            'create_by' => 'required|string',
        ];
        $validator = Validator::make($request->all(), $validate);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $dataInsert = $validator->validated();
        try {
            $insertBill = Bill::create($dataInsert);
            return response()->json([
                'message' => 'success',
                'idBill' => $insertBill->id,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bill = Bill::findOrFail($id);
        return response()->json($bill);
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

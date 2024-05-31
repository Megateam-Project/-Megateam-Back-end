<?php

namespace App\Repositories;
use App\Models\Booking;
class BookingRepository
{
    public function getAllBooking(){
        $bookings = Booking::with([
            'user' => function($query) {
                $query->select('id', 'email', 'name', 'phone');
            },
            'room' => function($query) {
                $query->select('id', 'number', 'price');
            }
        ])->get();
        return $bookings;
    } 
    public function createNewBooking(){

    }
    public function getDetailBooking($id){
        $booking = Booking::with('user', 'room')->find($id);
        return $booking;
    }
}

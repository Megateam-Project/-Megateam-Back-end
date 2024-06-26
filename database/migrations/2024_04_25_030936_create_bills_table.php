<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->decimal('total_price');
            $table->dateTime('date');
            $table->string('create_by')->nullable();
            $table->string('update_by')->nullable();
            $table->string('delete_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('booking_id')->references('id')->on('bookings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};

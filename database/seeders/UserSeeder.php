<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User();
        $user->name = 'user 1';
        $user->email = 'user1@gmail.com';
        $user->phone = '0987654321'; // Thêm số điện thoại tại đây nếu có
        $user->avatar = 'avatar.jpg'; // Thêm đường dẫn avatar tại đây nếu có
        $user->password = Hash::make('1345678');
        $user->role = 'user'; // Thêm vai trò tại đây nếu có
        $user->create_by = 1; // Thêm ID của người tạo tại đây nếu có
        $user->update_by = 1; // Thêm ID của người cập nhật tại đây nếu có
        $user->delete_by =  '2024-05-06 15:00:50'; // Đặt giá trị của delete_by là null
        $user->delete_at = '2024-05-06 15:00:50'; // Đặt giá trị của delete_at là null
        $user->save();
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = [
            'name' => 'Efren Goron',
            'email' => 'goronefren@gmail.com',
            'password' => Hash::make('superadmin@profile'),
            'role' => 'admin'
        ];

        $newUser = User::where('email', $admin['email'])->first();
        if (!$newUser) {
            User::create($admin);
        }
    }
}

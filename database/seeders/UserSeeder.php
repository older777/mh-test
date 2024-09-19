<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'last_name' => 'last',
            'name' => 'admin',
            'middle_name' => 'middle',
            'email' => 'admin@local.localhost',
            'phone' => '1234567',
            'password' => bcrypt('123'),
        ]);
    }
}

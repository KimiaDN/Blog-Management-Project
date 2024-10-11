<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'  => 'Masoume Riahy',
            'username' => 'Admin',
            'email' => 'kimiadarvishnoori1380@gmail.com',
            'password' => Hash::make('1234'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('Admin User Created Successfully');
        $this->command->info('username = Admin');
        $this->command->info('email = kimiadarvishnoori1380@gmail.com');
        $this->command->info('password = 1234');
        
    }
}

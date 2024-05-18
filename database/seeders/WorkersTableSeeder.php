<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Worker;

class WorkersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run()
    {
        Worker::create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => Hash::make('secretpassword'),
        ]);

        // Add more worker data as needed
        Worker::create([
            'name' => 'Jane Smith',
            'email' => 'janesmith@example.com',
            'password' => Hash::make('anothersecret'),
        ]);
    }
}

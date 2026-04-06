<?php

namespace Database\Seeders;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

public function run(): void
{
    Admin::create([
        'name' => 'Super Admin',
        'email' => 'admin@example.com',
        'password' => Hash::make('123456'),
    ]);
}



}

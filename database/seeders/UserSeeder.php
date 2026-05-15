<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => "Project Admin",
            'email' => 'projectadmin@mailinator.com',
            'password' => Hash::make('123456'),
            'mobile' => '8000000000',
            'role_id' => '1',
            'is_admin' => '1',
        ]);
    }
}

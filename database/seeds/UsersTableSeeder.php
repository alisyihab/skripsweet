<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Ali Syihab',
            'email' => 'ali.syihab@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('feelalive'),
            'role' => 0
        ]);
    }
}

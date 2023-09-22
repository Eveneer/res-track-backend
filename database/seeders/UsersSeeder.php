<?php

namespace Database\Seeders;

use App\Domain\User\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin', 
                'email' => 'admin@res-track.com',
                'password' => 'password'
            ],
            [
                'name' => 'Developer One', 
                'email' => 'developer.one@res-track.com',
                'password' => 'password'
            ],
            [
                'name' => 'Developer Two', 
                'email' => 'developer.two@res-track.com',
                'password' => 'password'
            ],
            [
                'name' => 'User One', 
                'email' => 'user.one@res-track.com',
                'password' => 'password'
            ],
            [
                'name' => 'User Two', 
                'email' => 'user.two@res-track.com',
                'password' => 'password'
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}

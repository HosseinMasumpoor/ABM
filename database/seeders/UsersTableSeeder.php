<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $users = User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Hossein',
            'email' => 'ahoseinmasumpoora@gmail.com',
            'password' => bcrypt('password')
        ]);
    }
}

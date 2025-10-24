<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate(
            ['email' => 'admin.a@gmail.com'],
            [
                'name' => 'Admin A',
                'password' => bcrypt('admina123'),
                'role' => 'admin'
            ]
        );

        User::firstOrCreate(
            ['email' => 'user.a@gmail.com'],
            [
                'name' => 'User A',
                'password' => bcrypt('usera123')
            ]
        );

        User::firstOrCreate(
            ['email' => 'user.b@gmail.com'],
            [
                'name' => 'User B',
                'password' => bcrypt('userb123')
            ]
        );
    }
}

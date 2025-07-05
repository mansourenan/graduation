<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::updateOrCreate([
            'email' => 'admin@admin.com',
        ], [
            'name' => 'Admin',
            'password' => bcrypt('admin1234'),
            'is_admin' => true,
        ]);
    }
}

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

    $this->call([
        StreamingProvidersSeeder::class,
    ]);

    User::factory()->create([
        'name' => 'hazael',
        'email' => 'test@example.com',
    ]);
}
}

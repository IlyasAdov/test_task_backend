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
        $email = fake()->unique()->safeEmail();
        $password = '12345678';
        $user = User::create([
            'name' => fake()->name(),
            'email' => $email,
            'password' => bcrypt($password),
        ]);

        $user->createToken('api-token')->plainTextToken;

        $this->command->info('email: '.$email);
        $this->command->info('password: '.$password);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $manager = User::create([
            'name' => 'manager',
            'email' => 'manager@app.com',
            'password' => Hash::make('12345678')
        ]);

        $manager->assignRole('manager');


        $user = User::create([
            'name' => 'user',
            'email' => 'user@app.com',
            'password' => Hash::make('12345678')
        ]);

        $user->factory(10)->create()->each(fn($user) => $user->assignRole('user'));
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $directorRole = Role::firstOrCreate(['name' => 'director']);

        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => 'password123',
            'role' => $directorRole->name,
        ]);

        $user->assignRole($directorRole);
    }
}

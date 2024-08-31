<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Define user data with roles and status
        $users = [
            ['username' => 'user1', 'name' => 'Ahmad Nur', 'email' => 'ahmad.nur@example.com', 'password' => 'password', 'roles' => ['Penyiar'], 'status' => 'active'],
            ['username' => 'user2', 'name' => 'Budi Santoso', 'email' => 'budi.santoso@example.com', 'password' => 'password', 'roles' => ['Penyiar'], 'status' => 'inactive'],
            ['username' => 'user3', 'name' => 'Citra Dewi', 'email' => 'citra.dewi@example.com', 'password' => 'password', 'roles' => ['Penyiar'], 'status' => 'active'],
            ['username' => 'user4', 'name' => 'Dani Putra', 'email' => 'dani.putra@example.com', 'password' => 'password', 'roles' => ['Penyiar'], 'status' => 'inactive'],
            ['username' => 'user5', 'name' => 'Eka Wulandari', 'email' => 'eka.wulandari@example.com', 'password' => 'password', 'roles' => ['Penyiar'], 'status' => 'active'],
            ['username' => 'user6', 'name' => 'Feri Hadi', 'email' => 'feri.hadi@example.com', 'password' => 'password', 'roles' => ['Penyiar', 'Koordinator Siaran'], 'status' => 'active'],
            ['username' => 'user7', 'name' => 'Gina Oktavia', 'email' => 'gina.oktavia@example.com', 'password' => 'password', 'roles' => ['Penyiar', 'Koordinator Siaran'], 'status' => 'inactive'],
            ['username' => 'user8', 'name' => 'Hendra Kusuma', 'email' => 'hendra.kusuma@example.com', 'password' => 'password', 'roles' => ['Koordinator Siaran'], 'status' => 'active'],
            ['username' => 'user9', 'name' => 'Indah Sari', 'email' => 'indah.sari@example.com', 'password' => 'password', 'roles' => ['Koordinator Siaran'], 'status' => 'inactive'],
            ['username' => 'user10', 'name' => 'Joko Prabowo', 'email' => 'joko.prabowo@example.com', 'password' => 'password', 'roles' => ['Kabid'], 'status' => 'active'],
            ['username' => 'user11', 'name' => 'Krisna Wijaya', 'email' => 'krisna.wijaya@example.com', 'password' => 'password', 'roles' => ['Kabid'], 'status' => 'inactive'],
            ['username' => 'user12', 'name' => 'Lina Sari', 'email' => 'lina.sari@example.com', 'password' => 'password', 'roles' => ['Kabid'], 'status' => 'active'],
            ['username' => 'user13', 'name' => 'Maya Anggraini', 'email' => 'maya.anggraini@example.com', 'password' => 'password', 'roles' => ['Kepala Siaran'], 'status' => 'active'],
            ['username' => 'user14', 'name' => 'Nina Fitria', 'email' => 'nina.fitria@example.com', 'password' => 'password', 'roles' => ['Kepala Siaran'], 'status' => 'inactive'],
        ];

        // Insert user data
        foreach ($users as $userData) {
            $user = User::create([
                'username' => $userData['username'],
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
                'status' => $userData['status'],
            ]);

            // Attach roles to the user
            $roles = Role::whereIn('role_name', $userData['roles'])->pluck('id');
            $user->roles()->attach($roles);
        }
    }
}

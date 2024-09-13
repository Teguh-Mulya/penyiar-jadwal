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

            ['username' => 'user1', 'name' => 'Widi Kurniawan', 'email' => 'widi.kur@example.com', 'password' => 'password', 'roles' => ['Kepala Stasiun'], 'status' => 'active'],
            ['username' => 'user2', 'name' => 'Dudi', 'email' => 'dudi@example.com', 'password' => 'password', 'roles' => ['Kepala Bidang Siaran'], 'status' => 'inactive'],
            ['username' => 'user3', 'name' => 'Zaki', 'email' => 'zaki@example.com', 'password' => 'password', 'roles' => ['Koordinator Siaran'], 'status' => 'active'],
            ['username' => 'user4', 'name' => 'Uli Tanuwijaya', 'email' => 'uli@example.com', 'password' => 'password', 'roles' => ['Penyiar'], 'status' => 'inactive'],
            ['username' => 'user5', 'name' => 'Raya Alifa', 'email' => 'alifa.raya@example.com', 'password' => 'password', 'roles' => ['Penyiar'], 'status' => 'active'],
            ['username' => 'user6', 'name' => 'Winda Anindita', 'email' => 'anindita.winda@example.com', 'password' => 'password', 'roles' => [ 'Penyiar'], 'status' => 'active'],
            ['username' => 'user7', 'name' => 'Randy Aditya', 'email' => 'aditya.randy@example.com', 'password' => 'password', 'roles' => [ 'Penyiar'], 'status' => 'inactive'],
            ['username' => 'user8', 'name' => 'Rangga Hadiansyah', 'email' => 'hadiansyah.rangga@example.com', 'password' => 'password', 'roles' => ['Penyiar'], 'status' => 'active'],
            ['username' => 'user9', 'name' => 'Sendy Puspa Andini', 'email' => 'andini.sendy@example.com', 'password' => 'password', 'roles' => ['Penyiar'], 'status' => 'inactive'],
            ['username' => 'user10', 'name' => 'Rio Risdianto', 'email' => 'kobe.rio@example.com', 'password' => 'password', 'roles' => ['Penyiar'], 'status' => 'active'],
           
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

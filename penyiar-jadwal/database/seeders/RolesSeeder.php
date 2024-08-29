<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            'Penyiar',
            'Koordinator Siaran',
            'Kabid',
            'Kepala Siaran',
        ];

        foreach ($roles as $role) {
            Role::create([
                'role_name' => $role,
                'status' => 'active',
            ]);
        }
    }
}

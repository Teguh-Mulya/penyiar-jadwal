<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = ['Penyiar', 'Koordinator Siaran', 'Kepala Bidang Siaran', 'Kepala Stasiun'];

        foreach ($roles as $role) {
            Role::create([
                'role_name' => $role,
                'status' => 'active',
            ]);
        }
    }
}


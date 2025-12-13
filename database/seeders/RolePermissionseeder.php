<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        //membuat beberapa role
        //membuat default user untuk super admin

        $ownerRole = Role::create([
            'name' => 'owner'
        ]);

        $studentRole = Role::create([
            'name' => 'student'
        ]);

        $studentRole = Role::create([
            'name' => 'teacher'
        ]);

        //akun super admin untuk mengelola data awal
        // data kategori, kelas, dsb
        $userOwner = User::create([
            'name' => 'Kikik Kakak',
            'occupation' => 'Edukator',
            'avatar' => 'images/default-avatar.png',
            'email' => 'Kikik@owner.com',
            'password' => bcrypt('123123123')
        ]);

        $userOwner->assignRole($ownerRole);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Buat Roles
        $rolePemilik = Role::create(['name' => 'admin']);
        $roleKaryawan = Role::create(['name' => 'karyawan']);

        // 2. Buat User Pemilik
        $pemilik = User::create([
            'name' => 'Pemilik Akun',
            'email' => 'admin@niskala.com',
            'password' => Hash::make('admin123'), // Ganti dengan password yang aman
        ]);
        $pemilik->assignRole($rolePemilik);

        $karyawan = User::create([
            'name' => 'Karyawan Akun',
            'email' => 'karyawan@niskala.com',
            'password' => Hash::make('karyawan123'), // Ganti dengan password yang aman
        ]);
        $karyawan->assignRole($roleKaryawan);
    }
}
<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createAdmin();
        $this->createSuperAdmin();

        User::factory()->count(50)->create();
    }

    public function createAdmin(): void
    {
        $adminEmail = 'admin@company.com';

        User::whereEmail($adminEmail)->existsOr(function () {
            User::factory()->create([
                'role' => Role::ADMIN,
                'password' => 'admin123',
                'email' => 'admin@company.com',
            ]);
        });
    }

    public function createSuperAdmin(): void
    {
        $superAdminEmail = 'super-admin@company.com';

        User::whereEmail($superAdminEmail)->existsOr(function () {
            User::factory()->create([
                'role' => Role::SUPER_ADMIN,
                'password' => 'superAdmin123',
                'email' => 'super-admin@company.com',
            ]);
        });
    }
}

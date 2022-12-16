<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Questionnaire;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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

        $users = User::factory()->count(50)->create();

        $this->assignQuestionnaires($users);
    }

    public function createAdmin(): void
    {
        $adminEmail = 'admin@company.com';

        User::whereEmail($adminEmail)->existsOr(function () {
            User::factory()->create([
                'role' => Role::ADMIN,
                'password' => Hash::make('admin123'),
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
                'password' => Hash::make('superAdmin123'),
                'email' => 'super-admin@company.com',
            ]);
        });
    }

    public function assignQuestionnaires(Collection $users): void
    {
        $users->each(function (User $user) {
            $questionnaireIds = Questionnaire::query()
                ->withCount('questions')
                ->completed(true)
                ->inRandomOrder()
                ->limit(random_int(1, 5))
                ->pluck('id');

            $user->questionnaires()->syncWithPivotValues($questionnaireIds, ['answers' => json_encode([])]);
        });
    }
}

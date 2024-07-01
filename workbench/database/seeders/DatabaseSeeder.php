<?php

declare(strict_types=1);

namespace Workbench\Database\Seeders;

use Illuminate\Database\Seeder;
use Workbench\App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; // password

        /** @var \Workbench\App\Models\User $admin */
        $admin = User::query()->create([
            'name' => 'Admin',
            'email' => 'admin@laravel.com',
            'password' => $password,
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
	public function run(): void
	{
		$adminEmail = env('ADMIN_EMAIL', 'admin@example.com');
		$adminName = env('ADMIN_NAME', 'Administrator');
		$adminPassword = env('ADMIN_PASSWORD', 'password');

		User::updateOrCreate(
			['email' => $adminEmail],
			[
				'name' => $adminName,
				'password' => Hash::make($adminPassword),
				'role' => 'admin',
			]
		);
	}
} 
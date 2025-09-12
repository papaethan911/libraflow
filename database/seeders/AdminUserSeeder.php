<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
	public function run(): void
	{
		$adminEmail = trim(strtolower(env('ADMIN_EMAIL', 'admin@example.com')));
		$adminName = env('ADMIN_NAME', 'Administrator');
		$adminPassword = env('ADMIN_PASSWORD', 'password');

		DB::table('users')->upsert([
			[
				'email' => $adminEmail,
				'name' => $adminName,
				'role' => 'admin',
				'password' => Hash::make($adminPassword),
				'updated_at' => now(),
				'created_at' => now(),
			],
		],
		['email'], // unique by email
		[
			'name', 'role', 'password', 'updated_at'
		]);
	}
} 
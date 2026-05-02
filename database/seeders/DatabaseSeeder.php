<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleAndAbilitySeeder::class);

        // ─── Admin User ───
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@pos.com',
            'password' => bcrypt('password'),
            'role_id'  => \App\Models\Role::where('name', 'admin')->first()->id,
            'pin_code' => '111111',
        ]);

        // ─── Cashier User ───
        User::create([
            'name'     => 'Cashier',
            'email'    => 'cashier@pos.com',
            'password' => bcrypt('password'),
            'role_id'  => \App\Models\Role::where('name', 'cashier')->first()->id,
            'pin_code' => '222222',
        ]);
    }
}

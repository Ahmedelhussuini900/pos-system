<?php

namespace Database\Seeders;

use App\Models\Ability;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleAndAbilitySeeder extends Seeder
{
    public function run(): void
    {
        // ─── Abilities ───
        $abilities = [
            ['name' => 'manage-categories', 'display_name' => 'إدارة الأقسام'],
            ['name' => 'manage-products',   'display_name' => 'إدارة الأصناف'],
            ['name' => 'create-order',      'display_name' => 'إنشاء طلب'],
            ['name' => 'cancel-order',      'display_name' => 'إلغاء طلب'],
            ['name' => 'view-orders',       'display_name' => 'عرض الطلبات'],
            ['name' => 'view-reports',      'display_name' => 'عرض التقارير'],
            ['name' => 'manage-users',      'display_name' => 'إدارة المستخدمين'],
            ['name' => 'manage-settings',   'display_name' => 'إدارة الإعدادات'],
        ];

        foreach ($abilities as $ability) {
            Ability::firstOrCreate(['name' => $ability['name']], $ability);
        }

        // ─── Admin Role (كل الصلاحيات) ───
        $admin = Role::firstOrCreate(
            ['name' => 'admin'],
            ['display_name' => 'مدير']
        );
        $admin->abilities()->sync(Ability::pluck('id'));

        // ─── Cashier Role ───
        $cashier = Role::firstOrCreate(
            ['name' => 'cashier'],
            ['display_name' => 'كاشير']
        );
        $cashier->abilities()->sync(
            Ability::whereIn('name', ['create-order', 'cancel-order', 'view-orders'])->pluck('id')
        );
    }
}

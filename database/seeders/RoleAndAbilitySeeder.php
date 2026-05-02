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
            ['name_ar' => 'إدارة الأقسام',    'name_en' => 'manage-categories', 'display_name_ar' => 'إدارة الأقسام',    'display_name_en' => 'Manage Categories'],
            ['name_ar' => 'إدارة الأصناف',    'name_en' => 'manage-products',   'display_name_ar' => 'إدارة الأصناف',    'display_name_en' => 'Manage Products'],
            ['name_ar' => 'إنشاء طلب',        'name_en' => 'create-order',      'display_name_ar' => 'إنشاء طلب',        'display_name_en' => 'Create Order'],
            ['name_ar' => 'إلغاء طلب',        'name_en' => 'cancel-order',      'display_name_ar' => 'إلغاء طلب',        'display_name_en' => 'Cancel Order'],
            ['name_ar' => 'عرض الطلبات',      'name_en' => 'view-orders',       'display_name_ar' => 'عرض الطلبات',      'display_name_en' => 'View Orders'],
            ['name_ar' => 'عرض التقارير',     'name_en' => 'view-reports',      'display_name_ar' => 'عرض التقارير',     'display_name_en' => 'View Reports'],
            ['name_ar' => 'إدارة المستخدمين', 'name_en' => 'manage-users',      'display_name_ar' => 'إدارة المستخدمين', 'display_name_en' => 'Manage Users'],
            ['name_ar' => 'إدارة الإعدادات',  'name_en' => 'manage-settings',   'display_name_ar' => 'إدارة الإعدادات',  'display_name_en' => 'Manage Settings'],
        ];

        foreach ($abilities as $ability) {
            Ability::firstOrCreate(['name_en' => $ability['name_en']], $ability);
        }

        // ─── Admin Role (كل الصلاحيات) ───
        $admin = Role::firstOrCreate(
            ['name_en' => 'admin'],
            ['name_ar' => 'مدير', 'display_name_ar' => 'مدير', 'display_name_en' => 'Admin']
        );
        $admin->abilities()->sync(Ability::pluck('id'));

        // ─── Cashier Role ───
        $cashier = Role::firstOrCreate(
            ['name_en' => 'cashier'],
            ['name_ar' => 'كاشير', 'display_name_ar' => 'كاشير', 'display_name_en' => 'Cashier']
        );
        $cashier->abilities()->sync(
            Ability::whereIn('name_en', ['create-order', 'cancel-order', 'view-orders'])->pluck('id')
        );
    }
}

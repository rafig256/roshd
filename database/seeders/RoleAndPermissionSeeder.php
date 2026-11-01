<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Seeder;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'users.view', 'users.create', 'users.edit', 'users.delete', 'users.block',
            'roles.view', 'roles.create', 'roles.edit', 'roles.delete', 'roles.assign',
            'innovators.requests.view', 'innovators.requests.approve',
            'plans.view', 'plans.review', 'plans.edit', 'plans.delete', 'plans.settings',
            'organizations.view', 'organizations.edit', 'organizations.delete', 'organizations.change_representative', 'organizations.extend_expiry', 'organizations.review_company', 'organizations.deactivate',
            'activity_categories.view', 'activity_categories.manage',
            'news.view', 'news.create', 'news.edit', 'news.delete', 'news.publish', 'news.categories.manage',
            'events.view', 'events.create', 'events.edit', 'events.delete', 'events.publish', 'events.categories.manage', 'events.speakers.manage', 'events.registrations.view',
            'media.view', 'media.upload', 'media.delete',
            'settings.view', 'settings.edit',
            'logs.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // --- نقش‌ها همراه با نام فارسی (label) ---

        $superAdmin = Role::updateOrCreate(
            ['name' => 'super_admin'],
            ['label' => 'مدیر کل']
        );
        $superAdmin->syncPermissions(Permission::all());

        $siteAdmin = Role::updateOrCreate(
            ['name' => 'site_admin'],
            ['label' => 'مدیر سایت']
        );
        $siteAdmin->syncPermissions(Permission::where('name', 'not like', 'roles.%')->get());

        $prOfficer = Role::updateOrCreate(
            ['name' => 'pr_officer'],
            ['label' => 'مدیر روابط عمومی']
        );
        $prOfficer->syncPermissions(
            Permission::where('name', 'like', 'news.%')
                ->orWhere('name', 'like', 'events.%')
                ->get()
        );

        $expert = Role::updateOrCreate(
            ['name' => 'expert'],
            ['label' => 'کارشناس مرکز رشد']
        );
        $expert->syncPermissions([
            'users.view',
            'innovators.requests.view', 'innovators.requests.approve',
            'plans.view', 'plans.review',
            'organizations.view', 'organizations.edit', 'organizations.delete', 'organizations.change_representative', 'organizations.extend_expiry', 'organizations.review_company', 'organizations.deactivate',
            'activity_categories.view', 'activity_categories.manage',
            'news.view',
            'events.view', 'events.create', 'events.edit', 'events.delete', 'events.publish', 'events.categories.manage', 'events.speakers.manage', 'events.registrations.view',
        ]);

        Role::updateOrCreate(
            ['name' => 'innovator'],
            ['label' => 'فناور']
        );
    }
}
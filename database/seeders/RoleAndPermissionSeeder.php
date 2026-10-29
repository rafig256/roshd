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

        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->syncPermissions(Permission::all());

        $siteAdmin = Role::firstOrCreate(['name' => 'site_admin']);
        // استفاده از فرم استاندارد کوئری
        $siteAdmin->syncPermissions(Permission::where('name', 'not like', 'roles.%')->get());

        $prOfficer = Role::firstOrCreate(['name' => 'pr_officer']);

        $prOfficer->syncPermissions(
            Permission::where('name', 'like', 'news.%')
                ->orWhere('name', 'like', 'events.%')
                ->get()
        );

        $expert = Role::firstOrCreate(['name' => 'expert']);
        $expert->syncPermissions([
            'users.view',
            'innovators.requests.view', 'innovators.requests.approve',
            'plans.view', 'plans.review',
            'organizations.view', 'organizations.edit', 'organizations.delete', 'organizations.change_representative', 'organizations.extend_expiry', 'organizations.review_company', 'organizations.deactivate',
            'activity_categories.view', 'activity_categories.manage',
            'news.view',
            'events.view', 'events.create', 'events.edit', 'events.delete', 'events.publish', 'events.categories.manage', 'events.speakers.manage', 'events.registrations.view',
        ]);

        Role::firstOrCreate(['name' => 'innovator']);
    }
}
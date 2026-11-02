<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionRouteNameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Clear cached permissions to avoid stale data
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $excluded = Admin::EXCLUDED_PERMISSIONS;
        $expectedPermissions = [];

        $adminRoutes = collect(Route::getRoutes())->filter(function ($route) use ($excluded) {
            $name = $route->getName();
            if (! $name || ! str_starts_with($name, 'admin.')) {
                return false;
            }

            if (str_ends_with($name, '.create')) {
                return false;
            }
            if (str_ends_with($name, '.add')) {
                return false;
            }

            if (in_array($name, $excluded, true)) {
                return false;
            }

            return true;
        });

        $patterns = config('patterns.admin');

        foreach ($patterns as $pattern) {
            $expectedPermissions[] = 'sms_' . $pattern['key'];
            $expectedPermissions[] = 'email_' . $pattern['key'];

            Permission::updateOrCreate([
                'name' => 'sms_' . $pattern['key'],
                'guard_name' => 'admin',
            ]);

            Permission::updateOrCreate([
                'name' => 'email_' . $pattern['key'],
                'guard_name' => 'admin',
            ]);
        }

        foreach ($adminRoutes as $route) {
            $expectedPermissions[] = $route->getName();

            Permission::updateOrCreate(
                [
                    'name' => $route->getName(),
                    'guard_name' => 'admin',
                ]
            );
        }

        // Remove only seeder-managed permissions that no longer exist (keeps role assignments for others)
        Permission::query()
            ->where('guard_name', 'admin')
            ->where(function ($query) {
                $query->where('name', 'like', 'admin.%')
                    ->orWhere('name', 'like', 'sms_%')
                    ->orWhere('name', 'like', 'email_%');
            })
            ->whereNotIn('name', $expectedPermissions)
            ->delete();

        $allPermissions = Permission::pluck('name')->toArray();

        // Only super-admin gets all permissions; other roles keep their existing assignments
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'admin']);
        $superAdmin->syncPermissions($allPermissions);

        $admin = Admin::find(1);
        if ($admin) {
            $admin->syncRoles('super-admin');
        }
    }

}

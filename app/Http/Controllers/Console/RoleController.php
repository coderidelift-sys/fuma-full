<?php

namespace App\Http\Controllers\Console;

use App\DataTables\RoleDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\RequestStoreRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    protected function getFormattedPermission()
    {
        $groupedPermissions = collect();

        $permissions = Permission::where('id', '>', '14')
            ->select('id', 'name')
            ->get()
            ->pluck('id', 'name');

        $headerWithoutUnderscore = $permissions->filter(function ($value, $key) {
            return strpos($key, '_') === false;
        });

        $dataPermission = $permissions->filter(function ($value, $key) {
            return strpos($key, '_') !== false;
        });

        function removePrefix($name, $prefix)
        {
            return str_replace($prefix . '_', '', $name);
        }

        foreach ($headerWithoutUnderscore as $headerKey => $headerValue) {
            $headerKeyNormalized = strtolower(str_replace(' ', '_', $headerKey));

            $filteredPermissions = $dataPermission->filter(function ($value, $key) use ($headerKeyNormalized) {
                return strpos($key, $headerKeyNormalized) !== false;
            });

            $permissionsWithoutPrefix = $filteredPermissions->mapWithKeys(function ($value, $key) use ($headerKeyNormalized) {
                $permissionName = str_replace($headerKeyNormalized . '_', '', $key);
                return [$permissionName => $value];
            });

            $groupedPermissions[$headerKeyNormalized] = $permissionsWithoutPrefix;
        }

        return $groupedPermissions;
    }

    public function index(RoleDataTable $dataTable)
    {
        $roles = Role::select('id', 'name')
        ->with('users:id,name,avatar')->withCount('users')->get();

        $permissions = $this->getFormattedPermission();

        return $dataTable->render('console.roles.index', compact('roles', 'permissions'));
    }

    public function store(RequestStoreRole $request)
    {
        try {
            DB::beginTransaction();

            $role = Role::create(['name' => $request->name]);

            if ($request->filled('all_permissions')) {
                $role->givePermissionTo(Permission::first());
            } else {
                $permissionIds = collect();

                $permissionHeaders = collect($request->permissions)->map(function ($permission) {
                    return str(explode(' | ', $permission)[1])->replace('_', ' ')->title()->__toString();
                })->unique();

                $permissionIds = collect($request->permissions)->map(function ($permission) {
                    return (int) explode(' | ', $permission)[0];
                });

                $permissionIds = Permission::whereIn('name', $permissionHeaders)->pluck('id')->merge($permissionIds);

                $role->givePermissionTo($permissionIds);
            }

            DB::commit();

            return redirect()->route('roles.index')->with('success', 'Role created successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Failed to create role: ' . $e->getMessage());
        }
    }

    public function update(RequestStoreRole $request, Role $role)
    {
        try {
            DB::beginTransaction();

            $role->update(['name' => request('name')]);

            $role->permissions()->detach();

            if (request('all_permissions')) {
                $role->givePermissionTo(Permission::first());
            } else {
                $permissionIds = collect();

                $permissionHeaders = collect($request->permissions)->map(function ($permission) {
                    return str(explode(' | ', $permission)[1])->replace('_', ' ')->title()->__toString();
                })->unique();

                $permissionIds = collect($request->permissions)->map(function ($permission) {
                    return (int) explode(' | ', $permission)[0];
                });

                $permissionIds = Permission::whereIn('name', $permissionHeaders)->pluck('id')->merge($permissionIds);

                $role->givePermissionTo($permissionIds);
            }

            DB::commit();

            return redirect()->route('roles.index')->with('success', 'Role updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Failed to update role: ' . $e->getMessage());
        }
    }

    public function destroy(Role $role)
    {
        try {
            $role->delete();

            return redirect()->route('roles.index')->with('success', 'Role deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete role: ' . $e->getMessage());
        }
    }
}

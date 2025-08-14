<?php

namespace App\Http\Controllers\Console;

use App\DataTables\PermissionDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\RequestStorePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(PermissionDataTable $dataTable)
    {
        return $dataTable->render('console.permissions.index');
    }

    public function create()
    {
        return view('console.permissions.create');
    }

    public function store(RequestStorePermission $request)
    {
        $payloadPermission = [
            'name' => $request->name,
            'guard_name' => 'web',
        ];

        try {
            DB::beginTransaction();

            Permission::create($payloadPermission);

            DB::commit();

            return redirect()->route('permissions.index')->with('success', 'Permission created successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to create permission:', [
                'error' => $e->getMessage(),
                'payload' => $payloadPermission
            ]);

            return back()->with('error', 'Failed to create permission. Please try again.');
        }
    }

    public function edit($id)
    {
        return view('console.permissions.edit');
    }

    public function update(RequestStorePermission $request, $id)
    {
        $payloadPermission = [
            'name' => $request->name,
            'guard_name' => 'web',
        ];

        try {
            DB::beginTransaction();

            $permission = Permission::findOrFail($id);
            $permission->update($payloadPermission);

            DB::commit();

            return redirect()->route('permissions.index')->with('success', 'Permission updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to update permission:', [
                'error' => $e->getMessage(),
                'payload' => $payloadPermission
            ]);

            return back()->with('error', 'Failed to update permission. Please try again.');
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $permission = Permission::findOrFail($id);
            $permission->delete();

            DB::commit();

            return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to delete permission', [
                'error' => $e->getMessage(),
                'permission_id' => $id
            ]);

            return back()->with('error', 'Failed to delete permission. Please try again.');
        }
    }
}

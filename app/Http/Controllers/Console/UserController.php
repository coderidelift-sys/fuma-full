<?php

namespace App\Http\Controllers\Console;

use App\DataTables\Scopes\UserRoleScope;
use App\DataTables\UserDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\RequestStoreUser;
use App\Http\Requests\RequestUserDetailUpdate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // Import DB facade
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request, UserDataTable $dataTable)
    {
        $roles = Role::select('id', 'name')->get();

        return $dataTable
            ->addScope(new UserRoleScope($request->input('role_id')))
            ->render('console.users.index', compact('roles'));
    }

    public function store(RequestStoreUser $request)
    {
        DB::beginTransaction();
        try {
            $user = User::create($request->validated());
            $user->assignRole(Role::find($request->role_id));

            DB::commit();
            return redirect()->route('users.index')->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction
            Log::error($e->getMessage());

            return back()->with('error', 'Failed to create user.');
        }
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            $roles = Role::select('id', 'name')->get();
            $user->role_id = $user->roles?->first()?->id ?? null;

            return view('console.users.show', compact('user', 'roles'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return back()->with('error', 'User not found.');
        }
    }

    public function edit($id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            $roles = Role::select('id', 'name')->get();
            $user->role_id = $user->roles?->first()?->id ?? null;

            DB::commit();
            return view('console.users.edit', compact('user', 'roles'));
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction
            Log::error($e->getMessage());

            return back()->with('error', 'User not found.');
        }
    }

    public function update(RequestStoreUser $request, $id)
    {
        DB::beginTransaction();
        try {
            $payloadUpdateUser = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            if ($request->password) {
                $payloadUpdateUser['password'] = bcrypt($request->password);
            }

            $user = User::findOrFail($id);
            $user->update($payloadUpdateUser);
            $user->syncRoles(Role::find($request->role_id));

            DB::commit();
            return redirect()->route('users.index')->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction
            Log::error($e->getMessage());

            return back()->with('error', 'Failed to update user.');
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            User::findOrFail($id)->delete();

            DB::commit();
            return redirect()->route('users.index')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction
            Log::error($e->getMessage());

            return back()->with('error', 'Failed to delete user.');
        }
    }

    public function updateDetail(RequestUserDetailUpdate $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            $user->update($request->validated());

            if ($request->hasFile('file_avatar')) {
                $user->avatar = handleUpload('file_avatar', '/avatars', $user->avatar);
            }

            $user->save();

            DB::commit();
            return back()->with('status', 'User updated!');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction
            Log::error($e->getMessage());

            return back()->with('error', 'Failed to update user.');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePermission;
use App\StatusCode;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return $user->getPermissionsViaRoles();
    }

    public function update(UpdatePermission $request)
    {
        $role = Role::where('name', $request->role)->first();
        $permissions = $request->validated()['permissions'];
        foreach ($permissions as $permissionKey => $permission) {
            if ($permission['is_active']) {
                $role->givePermissionTo($permission['name']);
            } else {
                $role->revokePermissionTo($permission['name']);
            }
        }
        return response()->successWithMessage('Berhasil mengubah izin hak akses!', StatusCode::OK);
    }
}

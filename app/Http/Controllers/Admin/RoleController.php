<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
       return view('admin.roles.index', [
            'roles' => Role::with('permissions')->get(),
            'permissions' => Permission::all()
        ]);
    }

    public function store(Request $request)
    {
        Role::create([
            'name' => $request->name
        ]);

        return back();
    }

    public function attachPermissions(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->permissions()->sync($request->permissions ?? []);
        return back();
    }
}

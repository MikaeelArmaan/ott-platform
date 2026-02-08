<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

class UserRoleController extends Controller
{
    public function index()
    {
        return view('admin.users.index', [
            'users' => User::with('roles')->get(),
            'roles' => Role::all()
        ]);
    }

    public function assign(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->roles()->sync($request->roles ?? []);
        return back();
    }
}

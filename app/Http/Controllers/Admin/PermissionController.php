<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        return view('admin.permissions.index', [
            'permissions' => Permission::all()
        ]);
    }

    public function store(Request $request)
    {
        Permission::create([
            'name' => $request->name
        ]);

        return back();
    }
}

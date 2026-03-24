@extends('admin.layouts.app')

@section('content')

<h1>Roles Management</h1>

{{-- Add Role --}}
<div class="card">
    <form method="POST" action="/admin/roles">
        @csrf
        <input type="text" name="name" placeholder="New role name" required>
        <button>Add Role</button>
    </form>
</div>

{{-- Roles Table --}}
<div class="card">
<table class="datatable">
    <thead>
        <tr>
            <th>Role Name</th>
            <th>Permissions</th>
            <th>Assign Permissions</th>
        </tr>
    </thead>
    <tbody>
    @foreach($roles as $role)
        <tr>
            <td>{{ $role->name }}</td>

            {{-- Existing permissions --}}
            <td>
                @foreach($role->permissions as $perm)
                    <span class="badge">{{ $perm->name }}</span>
                @endforeach
            </td>

            {{-- Assign permissions --}}
            <td>
                <form method="POST" action="/admin/roles/{{ $role->id }}/permissions">
                    @csrf
                    @foreach($permissions as $p)
                        <label style="display:inline-block;margin-right:10px;">
                            <input type="checkbox"
                                   name="permissions[]"
                                   value="{{ $p->id }}"
                                   {{ $role->permissions->contains($p->id) ? 'checked' : '' }}>
                            {{ $p->name }}
                        </label>
                    @endforeach
                    <br><br>
                    <button>Save</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>

@endsection

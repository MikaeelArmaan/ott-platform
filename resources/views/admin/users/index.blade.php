@extends('admin.layouts.app')

@section('content')

<h1>Users & Roles</h1>

<div class="card">
<table class="datatable">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Roles</th>
            <th>Assign Roles</th>
        </tr>
    </thead>
    <tbody>
    @foreach($users as $u)
        <tr>
            <td>{{ $u->name }}</td>
            <td>{{ $u->email }}</td>
            <td>
                @foreach($u->roles as $r)
                    <span class="badge">{{ $r->name }}</span>
                @endforeach
            </td>
            <td>
                <form method="POST" action="/admin/users/{{ $u->id }}/roles">
                    @csrf
                    @foreach($roles as $r)
                        <label>
                            <input type="checkbox"
                                name="roles[]"
                                value="{{ $r->id }}"
                                {{ $u->roles->contains($r->id) ? 'checked' : '' }}>
                            {{ $r->name }}
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

@extends('admin.layout.app')

@section('content')

<h1>Roles</h1>

<div class="card">
<form method="POST" action="/admin/roles">
@csrf
<input name="name" placeholder="Role name">
<button>Add Role</button>
</form>
</div>

@foreach($roles as $role)
<div class="card">
<h3>{{ $role->name }}</h3>

<form method="POST" action="/admin/roles/{{ $role->id }}/permissions">
@csrf

@foreach($permissions as $p)
<label>
<input type="checkbox" name="permissions[]" value="{{ $p->id }}"
{{ $role->permissions->contains($p->id) ? 'checked':'' }}>
{{ $p->name }}
</label>
@endforeach

<br><br>
<button>Save</button>
</form>

</div>
@endforeach

@endsection

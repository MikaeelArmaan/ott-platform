@extends('admin.layout.app')

@section('content')

<h1>User Role Management</h1>

@foreach($users as $u)
<div class="card">

<b>{{ $u->name }}</b> ({{ $u->email }})

<form method="POST" action="/admin/users/{{ $u->id }}/roles">
@csrf

@foreach($roles as $r)
<label>
<input type="checkbox" name="roles[]" value="{{ $r->id }}"
{{ $u->roles->contains($r->id) ? 'checked':'' }}>
{{ $r->name }}
</label>
@endforeach

<br><br>
<button>Update Roles</button>

</form>
</div>
@endforeach

@endsection

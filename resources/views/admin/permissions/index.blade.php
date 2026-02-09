@extends('admin.layout.app')

@section('content')

<h1>Permissions</h1>

<div class="card">
<form method="POST" action="/admin/permissions">
@csrf
<input name="name" placeholder="Permission name">
<button>Add Permission</button>
</form>
</div>

@foreach($permissions as $p)
<div class="card">
{{ $p->name }}
</div>
@endforeach

@endsection

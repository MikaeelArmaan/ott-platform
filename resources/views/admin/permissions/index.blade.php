@extends('admin.layouts.app')

@section('content')

<h1>Permissions</h1>

<div class="card">
<form method="POST" action="/admin/permissions">
    @csrf
    <input name="name" placeholder="New permission">
    <button>Add Permission</button>
</form>
</div>

<div class="card">
<table class="datatable">
    <thead>
        <tr>
            <th>Permission Name</th>
        </tr>
    </thead>
    <tbody>
        @foreach($permissions as $p)
        <tr>
            <td>{{ $p->name }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>

@endsection

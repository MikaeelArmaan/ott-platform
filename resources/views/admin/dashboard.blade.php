@extends('admin.layout.app')

@section('content')

<h1>Dashboard</h1>

<div class="card">
    <h3>Welcome {{ auth()->user()->name }}</h3>
    <p>OTT Platform Admin Panel</p>
</div>

@endsection

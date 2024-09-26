<!-- resources/views/dashboard/admin.blade.php -->

@extends('main')

@section('content')
    <h1>Teacher Dashboard</h1>
    <p>
        Welcome to the Teacher dashboard!
        <b> {{ strtoupper($user->name) }}</b>

    </p>
@endsection

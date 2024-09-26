<!-- resources/views/dashboard/superAdmin.blade.php -->

@extends('main')

@section('content')
    <h1>Administrator Dashboard</h1>
    <p>Welcome to the Administrator dashboard!<b> {{ strtoupper($user->name) }}</b></p>
@endsection

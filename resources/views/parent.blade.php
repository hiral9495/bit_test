<!-- resources/views/dashboard/member.blade.php -->

@extends('main')

@section('content')
    <h1>Parent Dashboard</h1>
    <p>Welcome to the Parent dashboard!
    <b> {{ strtoupper($user->name) }}</b>
    </p>
@endsection

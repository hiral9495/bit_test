<!-- resources/views/dashboard/editor.blade.php -->

@extends('main')

@section('content')
    <h1>Student Dashboard</h1>
    <p>Welcome to the Student dashboard!
    <b> {{ strtoupper($user->name) }}</b>
    </p>
@endsection
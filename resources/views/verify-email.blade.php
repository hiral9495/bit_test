@extends('loginLayout')

@section('content')
<div class="container">
    <div class="alert alert-success" role="alert">
        A fresh verification link has been sent to your email address.
    </div>
    <p>Please check your email for a verification link.</p>
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-primary">Resend Verification Email</button>
    </form>
</div>
@endsection

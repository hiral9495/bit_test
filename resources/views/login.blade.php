@extends('loginLayout')
@section('content') 
 
 @if($message = Session::get('success'))

<div class="alert alert-info">
    {{ $message }}
</div>

@endif
@if($message = Session::get('error'))

<div class="alert alert-danger">
    {{ $message }}
</div>

@endif

<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header header-text">LOGIN</div>

            <div class="card-body">
                <form action="{{ route('sample.validate_login') }}" method="post">
                    @csrf
                    <div class="form-group mb-3">
                    <input type="text" name="email_or_username" class="form-control" placeholder="Email or username" />
                         @if($errors->has('email_or_username'))
                        <span class="text-danger">{{ $errors->first('email_or_username') }}</span> @endif
                    </div>
                    <div class="form-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" value="{{session('password')}}" /> @if($errors->has('password'))
                        <span class="text-danger">{{ $errors->first('password') }}</span> @endif
                    </div>
                    <div class="row mb-4">
                        <div class="col d-flex justify-content-center">
                            <!-- Checkbox -->
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="remember" id="remember" class="custom-control-input" {{ old( 'remember') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="remember">Remember Me</label>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <!-- Simple link -->
                            <a href="{{ route('password.request') }}">Forgot password?</a>
                        </div>
                    </div>
                    <div class="d-grid mx-auto">
                        <button type="subit" class="btn btn-primary btn-block mb-4">Login</button>
                    </div>
                    <div class="text-center">
                        <p>Not a member? <a href="{{ route('registration') }}">Register</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection('content')
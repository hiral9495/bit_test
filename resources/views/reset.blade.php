@extends('loginLayout')
@section('content')
<div class="container">
   <div class="row justify-content-center">
      <div class="col-md-4">
         <div class="card">
            <div class="card-header header-text">RESET PASSWORD</div>
            <div class="card-body">
               <form method="POST" action="{{ route('password.update') }}">
                  @csrf
                  <input type="hidden" name="token" value="{{ $token }}">
                  <div class="form-group mb-3">
                     <label for="email" class="col-form-label text-md-right"><b>Email Address</b></label>
                     <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">
                     @if($errors->has('email'))
                     <span class="text-danger">{{ $errors->first('email') }}</span>
                     @endif
                  </div>
                  <div class="form-group mb-3">
                     <label for="password" class="form-label"><b>Password</b></label>
                     <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="{{session('password')}}">
                     @if($errors->has('password'))
                     <span class="text-danger">{{ $errors->first('password') }}</span>
                     @endif
                  </div>
                  <div class="form-group mb-3">
                     <label for="password-confirm" class="col-form-label text-md-right"><b>Confirm Password</b></label>
                     <input id="password-confirm" type="password" class="form-control" name="confirmPassword" value="{{session('confirmPassword')}}">
                     @if($errors->has('confirmPassword'))
                     <span class="text-danger">{{ $errors->first('confirmPassword') }}</span>
                     @endif
                  </div>
                  <div class="d-grid mx-auto">
                     <button type="submit" class="btn btn-primary">
                        Reset Password
                     </button>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
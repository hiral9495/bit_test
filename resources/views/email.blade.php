@extends('loginLayout')
@section('content')
<div class="container">
   <div class="row justify-content-center">
      <div class="col-md-4">
         <div class="card">
            <div class="card-header header-text">RESET PASSWORD</div>
            <div class="card-body">
               @if (session('status'))
               <div class="alert alert-success" role="alert">
                  {{ session('status') }}
               </div>
               @endif
               <form method="POST" action="{{ route('password.email') }}">
                  @csrf
                  <div class="form-group mb-3">
                     <label for="email" class="col-form-label text-md-right"></b>Email Address<b></label>
                     <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">
                     @if($errors->has('email'))
                     <span class="text-danger">{{ $errors->first('email') }}</span>
                     @endif
                  </div>
                  <div class="d-grid mx-auto">
                     <button type="submit" class="btn btn-primary">
                        Send Password Reset Link
                     </button>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
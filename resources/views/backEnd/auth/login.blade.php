@extends('layouts.backend.auth.admin-auth')
@section('admin-auth')

<!-- Nested Row within Card Body -->
<div class="row">
    <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
    <div class="col-lg-6">
        <div class="p-5">
            <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
            </div>
            <form class="user" action="{{route('login')}}" method="POST">
                @csrf
                <div class="form-group">
                    <input type="text" class="@error('username') is-invalid @enderror form-control form-control-user"
                        id="exampleInputEmail" placeholder="Enter  username..." name="username">
                    @error('username')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control form-control-user"
                        id="exampleInputPassword" placeholder="Password">
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <div class="custom-control custom-checkbox small">
                        <input type="checkbox" class="custom-control-input" id="customCheck">
                        <label class="custom-control-label" for="customCheck">Remember
                            Me</label>
                    </div>
                </div>
                <button href="" class="btn btn-primary btn-user btn-block">
                    Login
                </button>
                <hr>
                <hr>
                <div class="text-center">
                    @if (Route::has('password.request'))
                    <a class="btn btn-link py-5" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                    @endif
                </div>
            </form>


        </div>
    </div>
</div>
@endsection

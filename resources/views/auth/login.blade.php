@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row">

        <div class="col-6 offset-3 py-5">
            <h5 class="py-5">{{__('LOGIN')}}</h5>

            <form method="POST" action="{{ route('login') }}" autocomplete="off">
                @csrf
                <div class="form-group row">
                    <label for="inputPassword"
                        class="col-md-4 col-form-label text-md-right">{{ __('USERNAME') }}</label>
                    <div class="col-8">
                        <input type="text" placeholder="enter your username" name="username" class="form-control @error('username') is-invalid @enderror"
                            id="inputPassword">
                        @error('username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword"
                        class="col-md-4 col-form-label text-md-right">{{ __('PASSWORD') }}</label>
                    <div class="col-8">
                        <input type="password" placeholder="enter your password" name="password" class="form-control @error('password') is-invalid @enderror"
                            id="inputPassword">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>


                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                {{ old('remember') ? 'checked' : '' }}>

                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-12 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Login') }}
                        </button>
                        <a  class="btn btn-outline-primary" href="{{ route('register') }}">
                            {{ __('Haven’t an account ?') }}
                        </a>
                        @if (Route::has('password.request'))
                        <a class="btn btn-link py-5" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                        @endif

                        
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection

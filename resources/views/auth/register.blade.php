@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-6 offset-3 py-5">
            <h5 class="py-5">{{ __('Register') }}</h5>

            <form method="POST" action="{{ route('register') }}" autocomplete="off">
                @csrf

                <div class="form-group row">
                    <label for="inputPassword" class="col-md-4 col-form-label text-md-right">{{ __('first_name') }}</label>
                    <div class="col-8">
                        <input type="text" placeholder="enter your first_name" name="first_name"
                            class="form-control @error('first_name') is-invalid @enderror" id="inputPassword">
                        @error('first_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputPassword" class="col-md-4 col-form-label text-md-right">{{ __('laset_name') }}</label>
                    <div class="col-8">
                        <input type="text" placeholder="enter your laset_name" name="laset_name"
                            class="form-control @error('laset_name') is-invalid @enderror" id="inputPassword">
                        @error('laset_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputPassword" class="col-md-4 col-form-label text-md-right">{{ __('username') }}</label>
                    <div class="col-8">
                        <input type="text" placeholder="enter your username" name="username"
                            class="form-control @error('username') is-invalid @enderror" id="inputPassword">
                        @error('username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputPassword" class="col-md-4 col-form-label text-md-right">{{ __('mobile number') }}</label>
                    <div class="col-8">
                        <input type="text" placeholder="enter your mobile" name="mobile"
                            class="form-control @error('mobile') is-invalid @enderror" id="inputPassword">
                        @error('mobile')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputPassword" class="col-md-4 col-form-label text-md-right">{{ __('email') }}</label>
                    <div class="col-8">
                        <input type="email" placeholder="enter your email" name="email"
                            class="form-control @error('email') is-invalid @enderror" id="inputPassword">
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputPassword"
                        class="col-md-4 col-form-label text-md-right">{{ __('password') }}</label>
                    <div class="col-8">
                        <input type="password" placeholder="enter your password" name="password"
                            class="form-control @error('password') is-invalid @enderror" id="inputPassword">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputPassword"
                        class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>
                    <div class="col-8">
                        <input type="password" autocomplete="new-password" placeholder="password_confirmation"
                            name="password_confirmation" class="form-control" id="inputPassword">

                    </div>
                </div>



                {{-- <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                <div class="col-md-6">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                        required autocomplete="new-password">
                </div>
        </div> --}}

        <div class="form-group row mb-0">
            <div class="col-12 offset-md-4 py-3">
                <button type="submit" class="btn btn-primary">
                    {{ __('Register') }}
                </button>
                <a href="{{route('login')}}" class="btn btn-outline-primary">{{__('have an account ?')}}</a>
            </div>
        </div>
        </form>


    </div>
</div>
</div>
@endsection


@extends('layouts.auth')

@section('body_class','fp-page')

@section('content')

    <div class="fp-box login-box">


        <div class="card">
            <div class="body">
                <form id="reset_password" method="POST" action="{{ route('password.update') }}">

                    {{ csrf_field() }}

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="msg">

                        Reset Password

                    </div>

                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">email</i>
                        </span>

                        <div class="form-line {{ $errors->has('email') ? ' error' : '' }}">
                            <input id="email" type="text" class="form-control" name="email" value="{{ $user->email }}">
                        </div>

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                             </span>
                        @endif
                    </div>

                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>

                        <div class="form-line {{ $errors->has('password') ? ' error' : '' }}">
                            <input id="password" type="password" class="form-control" name="password" value="{{ old('password') }}" autofocus>
                        </div>
                        @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif

                    </div>

                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>

                        <div class="form-line {{ $errors->has('password_confirmation') ? ' error' : '' }}">
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" value="{{ old('password_confirmation') }}" autofocus>
                        </div>

                    </div>

                    <button class="btn  btn-lg bg-pink waves-effect" type="submit">RESET MY PASSWORD</button>

                    <div class="row m-t-20 m-b--5 align-center">
                        <a class="font-bold" href="{{ route('login') }}">Sign In!</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection



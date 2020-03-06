@extends('layouts.auth')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card-group">
            <div class="card p-4">
                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="verification_token" value="{{ $user->verification_token ?? '' }}" />
                        <h1>
                            <div class="login-logo">
                                <a href="#">
                                    {{ trans('panel.site_title') }}
                                </a>
                            </div>
                        </h1>

                        
                        @if(session('message'))
                            <div class="alert alert-success" role="alert">{!! session('message') !!}</div>
                        @else
                            <div>
                                <div class="form-group has-feedback">
                                    <input type="email" name="email" value="{{ $user->email }}" class="form-control" disabled>
                                </div>
                                <div class="form-group has-feedback">
                                    <input type="password" name="password" class="form-control" required placeholder="{{ trans('global.login_password') }}">
                                </div>
                                <div class="form-group has-feedback">
                                    <input type="password" name="password_confirmation" class="form-control" required placeholder="{{ trans('global.login_password_confirmation') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 text-right">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat">
                                        {{ trans('global.set_password') }}
                                    </button>
                                </div>
                            </div>
                        @endif

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
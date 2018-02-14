@extends('layouts.empty')

@section('content')

    <div class="login-box-body">
        <p class="login-box-msg">Reset Password</p>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form role="form" method="POST" action="{{ route('password.email') }}">
            {{ csrf_field() }}
            
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}  has-feedback">
                <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Email">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}  has-feedback">
                <input id="password" type="password" name="password" value="{{ old('email') }}" class="form-control" placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}  has-feedback">
                <input id="password-confirm" type="password" name="password_confirmation" value="{{ old('email') }}" class="form-control" placeholder="Confirm Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                @if ($errors->has('password_confirmation'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </span>
                @endif
            </div>


            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">
                        Reset Password
                    </button>
                </div>
            </div>
        </form>

    </div>

@endsection

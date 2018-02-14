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
            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}  has-feedback">
                <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Email">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">
                        Send Password Reset Link
                    </button>
                </div>
            </div>
        </form>

    </div>

@endsection

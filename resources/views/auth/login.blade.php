@extends('layouts.auth', ['pageTitle' => __('Login')])

@section('card-tabs')
    <li>
        <a href="/register">{{ __('Register') }}</a>
    </li>
@endsection

@section('content')

    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        <b-field label="{{ __('Username or E-Mail Address') }}"
                 @if ($errors->has('username'))
                 type="is-danger"
                @endif
                 message="{{ $errors->first('username') }}">
            <b-input value="{{ old('email') }}"
                     name="username"
                     type="text"
                     required
                     autofocus>
            </b-input>
        </b-field>

        <b-field label="{{ __('Password') }}"
                 @if ($errors->has('password'))
                 type="is-danger"
                 message="{{ $errors->first('password') }}"
                @endif
        >
            <b-input value="{{ old('password') }}"
                     name="password"
                     type="password"
                     required
                     password-reveal
                     autofocus>
            </b-input>
        </b-field>

        <div class="field">
            <b-checkbox name="remember" {{ old('remember') ? 'checked' : '' }}>
                {{ __('Remember Me') }}
            </b-checkbox>
        </div>

        <div class="field">
            <div class="control">
                <button type="submit" class="button is-primary">
                    {{ __('Login') }}
                </button>

                <a class="button is-text" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            </div>
        </div>
    </form>
@endsection

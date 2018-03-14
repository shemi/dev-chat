@extends('layouts.auth', ['pageTitle' => __('Register')])

@section('card-tabs')
    <li>
        <a href="/login">{{ __('Login') }}</a>
    </li>
@endsection

@section('content')

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <b-field label="{{ __('Name') }}"
                 @if ($errors->has('name'))
                 type="is-danger"
                 message="{{ $errors->first('name') }}"
                @endif
        >
            <b-input value="{{ old('name') }}"
                     name="name"
                     type="text"
                     required
                     autofocus>
            </b-input>
        </b-field>

        <b-field label="{{ __('Username') }}"
                 @if ($errors->has('username'))
                 type="is-danger"
                 message="{{ $errors->first('username') }}"
                @endif
        >
            <b-input value="{{ old('username') }}"
                     name="username"
                     type="text"
                     required>
            </b-input>
        </b-field>

        <b-field label="{{ __('E-Mail Address') }}"
                 @if ($errors->has('email'))
                 type="is-danger"
                 message="{{ $errors->first('email') }}"
                @endif
        >
            <b-input value="{{ old('email') }}"
                     name="email"
                     type="email"
                     required>
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
                     password-reveal
                     required>
            </b-input>
        </b-field>

        <div class="field">
            <div class="control">
                <button type="submit" class="button is-primary">
                    {{ __('Register') }}
                </button>
            </div>
        </div>
    </form>
@endsection

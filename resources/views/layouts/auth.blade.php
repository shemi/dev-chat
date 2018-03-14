@extends('layouts.base')

@section('base-content')

    <div class="auth-page hero is-medium is-primary is-bold">

        <h1 class="logo">
            <b-icon icon="comment-alt"></b-icon>
            <span>DevChat</span>
        </h1>

        <div class="card auth-card">
            <header class="card-header">
                <div class="tabs">
                    <ul>
                        <li class="is-active">
                            <a>{{ $pageTitle ?? 'NO TITLE' }}</a>
                        </li>
                        @yield('card-tabs')
                    </ul>
                </div>
            </header>
            <div class="card-content">
                @yield('content')
            </div>
            <footer class="card-footer">
                @yield('card-footer')
            </footer>
        </div>

    </div>

@endsection
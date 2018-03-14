@extends('layouts.base')

@section('base-content')

    <div class="app-page">
        <header class="app-header">
            <nav class="navbar is-primary" role="navigation" aria-label="main navigation">
                <div class="container">
                    <div class="navbar-brand">
                        <a class="navbar-item" href="/">
                            <h1 class="logo">
                                <b-icon icon="comment-alt"></b-icon>
                                <span>DevChat</span>
                            </h1>
                        </a>
                    </div>
                </div>
            </nav>
        </header>

        <main class="app-content">
            @yield('content')
        </main>
    </div>

@endsection
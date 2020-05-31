{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

<!doctype html>
<html lang="{{ App::getLocale()  }}">
    <head>
        @include('layouts.layout_ga')

        <meta charset="utf-8">
        <meta name="author" content="{{ env('APP_AUTHOR') }}">
        <meta name="description" content="{{ env('APP_DESCRIPTION') }}">
        <meta name="tags" content="{{ env('APP_TAGS') }}">

        <link rel="stylesheet" type="text/css" href="{{ asset('css/bulma.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/metro-all.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">

        <script src="{{ asset('js/fontawesome.js') }}"></script>
        <script src="{{ asset('js/metro.min.js') }}"></script>
        <script src="{{ asset('js/push.min.js') }}"></script>
        @if (env('APP_ENV') == 'local')
            <script src="{{ asset('js/vue.js') }}"></script>
        @else
            <script src="{{ asset('js/vue.min.js') }}"></script>
        @endif
        <script src="{{ asset('js/axios.min.js') }}"></script>

        <title>@yield('title')</title>
    </head>

    <body class="is-member-background">
        <nav class="navbar has-border-bottom is-fixed-top" role="navigation" aria-label="main navigation">
            <div class="navbar-brand">
                <a class="navbar-item is-app-title" href="{{ url('/') }}">
                    <strong>{{ \App\AppModel::getNameParts()[0] }}</strong>{{ \App\AppModel::getNameParts()[1] }}
                </a>

                <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarMenu">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </a>
            </div>

            <div id="navbarMenu" class="navbar-menu">
                <div class="navbar-start">

                </div>

                <center>
                    <div class="field navbar-search">
                        <p class="control has-icons-right">
                            <input type="text" name="hashtag" placeholder="{{ __('app.search_jump_to_hashtag') }}" onkeypress="if (event.which === 13) location.href='{{ url('/t') }}/' + this.value;">
                            <span class="icon is-small is-right is-top-navbar">
                                <i class="fas fa-search"></i>
                            </span>
                        </p>
                    </div>
                </center>

                <div class="navbar-end">
                    <div class="navbar-item">
                        <div>
                            <i class="fas fa-upload fa-lg is-pointer" title="{{ __('app.member_upload') }}" onclick="location.href='{{ url('/upload') }}';"></i>
                        </div>
                    </div>

                    <div class="navbar-item">
                        <div>
                            <i class="far fa-heart fa-lg is-pointer" title="{{ __('app.notifications') }}"  onclick="location.href='{{ url('/notifications') }}';"></i>
                        </div>
                    </div>

                    <div class="navbar-item">
                        <div>
                            <img class="avatar is-pointer" src="{{ asset('gfx/avatars/' . $user->avatar) }}" title="{{ __('app.profile') }}"  onclick="location.href='{{ url('/profile') }}';">
                        </div>
                    </div>

                    <div class="navbar-item">
                        <div>
                            <i class="fas fa-sign-out-alt fa-lg is-pointer" title="{{ __('app.logout') }}"  onclick="location.href='{{ url('/logout') }}';"></i>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <div id="main" class="container">
            @if ($errors->any())
                <div id="error-message-1" class="is-z-index-3">
                    <article class="message is-danger">
                        <div class="message-header">
                            <p>{{ __('app.error') }}</p>
                            <button class="delete" aria-label="delete" onclick="document.getElementById('error-message-1').style.display = 'none';"></button>
                        </div>
                        <div class="message-body">
                            @foreach ($errors->all() as $error)
                                {{ $error }}<br/>
                            @endforeach
                        </div>
                    </article>
                </div>
                <br/>
            @endif

            @if (Session::has('error'))
                <div id="error-message-2" class="is-z-index-3">
                    <article class="message is-danger">
                        <div class="message-header">
                            <p>{{ __('app.error') }}</p>
                            <button class="delete" aria-label="delete" onclick="document.getElementById('error-message-2').style.display = 'none';"></button>
                        </div>
                        <div class="message-body">
                            {{ Session::get('error') }}
                        </div>
                    </article>
                </div>
                <br/>
            @endif

            @if (Session::has('notice'))
                <div id="notice-message" class="is-z-index-3">
                    <article class="message is-info">
                        <div class="message-header">
                            <p>{{ __('app.notice') }}</p>
                            <button class="delete" aria-label="delete" onclick="document.getElementById('notice-message').style.display = 'none';"></button>
                        </div>
                        <div class="message-body">
                            {{ Session::get('notice') }}
                        </div>
                    </article>
                </div>
                <br/>
            @endif

            @if (Session::has('success'))
                <div id="success-message" class="is-z-index-3">
                    <article class="message is-success">
                        <div class="message-header">
                            <p>{{ __('app.success') }}</p>
                            <button class="delete" aria-label="delete" onclick="document.getElementById('success-message').style.display = 'none';"></button>
                        </div>
                        <div class="message-body">
                            {{ Session::get('success') }}
                        </div>
                    </article>
                </div>
                <br/>
            @endif

            <div class="columns is-vcentered is-multiline">
                @yield('body')
            </div>
        </div>
    </body>

    <script src="{{ asset('js/app.js') }}"></script>
    @yield('javascript')
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // Get all "navbar-burger" elements
            const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

            // Check if there are any navbar burgers
            if ($navbarBurgers.length > 0) {

                // Add a click event on each of them
                $navbarBurgers.forEach( el => {
                    el.addEventListener('click', () => {

                        // Get the target from the "data-target" attribute
                        const target = el.dataset.target;
                        const $target = document.getElementById(target);

                        // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
                        el.classList.toggle('is-active');
                        $target.classList.toggle('is-active');

                    });
                });
            }
        });
    </script>
</html>

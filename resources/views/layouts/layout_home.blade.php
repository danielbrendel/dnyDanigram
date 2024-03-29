{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2022 by Daniel Brendel

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
        <meta name="description" content="{{ env('APP_DESCRIPTION') }}">
        <meta name="keywords" content="{{ env('APP_TAGS') }}">

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" type="text/css" href="{{ asset('css/bulma.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/metro-all.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ \App\ThemeModel::getThemeToInclude() }}">

        <link rel="icon" type="image/png" href="{{ asset('/logo.png') }}">

        <script src="{{ asset('js/fontawesome.js') }}"></script>
        <script src="{{ asset('js/metro.min.js') }}"></script>
        <script src="{{ asset('js/push.min.js') }}"></script>
        @if (env('APP_ENV') == 'local')
            <script src="{{ asset('js/vue.js') }}"></script>
        @else
            <script src="{{ asset('js/vue.min.js') }}"></script>
        @endif

        <title>@yield('title')</title>

        @if (strlen(\App\AppModel::getHeadCode()) > 0)
            {!! \App\AppModel::getHeadCode() !!}
        @endif
    </head>

    <body>
        <div id="cookie-consent" class="cookie-consent has-text-centered">
            <div class="cookie-consent-inner">
                {!! $cookie_consent !!}
            </div>

            <div class="cookie-consent-button">
                <div onclick="vue.clickedCookieConsentButton()"><i class="fas fa-times is-pointer" title="{{ __('app.cookie_consent_close') }}"></i></div>
            </div>
        </div>

        <div id="main" class="container">
            @if ($errors->any())
                <div id="error-message-1">
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
                <div id="error-message-2">
                    <article class="message is-danger">
                        <div class="message-header">
                            <p>{{ __('app.error') }}</p>
                            <button class="delete" aria-label="delete" onclick="document.getElementById('error-message-2').style.display = 'none';"></button>
                        </div>
                        <div class="message-body">
                            {!! Session::get('error') !!}
                        </div>
                    </article>
                </div>
                <br/>
            @endif

            @if (Session::has('success'))
                <div id="success-message">
                    <article class="message is-success">
                        <div class="message-header">
                            <p>{{ __('app.success') }}</p>
                            <button class="delete" aria-label="delete" onclick="document.getElementById('success-message').style.display = 'none';"></button>
                        </div>
                        <div class="message-body">
                            {!! Session::get('success') !!}
                        </div>
                    </article>
                </div>
                <br/>
            @endif

            <div class="columns is-vcentered is-multiline">
                @yield('body')
            </div>

                <div class="modal" :class="{'is-active': bShowRegister}">
                    <div class="modal-background"></div>
                    <div class="modal-card">
                        <header class="modal-card-head is-stretched">
                            <p class="modal-card-title">{{ __('app.register') }}</p>
                            <button class="delete" aria-label="close" onclick="vue.bShowRegister = false;"></button>
                        </header>
                        <section class="modal-card-body is-stretched">
                            <form id="regform" method="POST" action="{{ url('/register') }}">
                                @csrf

                                <div class="field">
                                    <label class="label">{{ __('app.register_username') }}</label>
                                    <div class="control">
                                        <input class="input" type="text" name="username" required>
                                    </div>
                                </div>

                                <div class="field">
                                    <label class="label">{{ __('app.register_email') }}</label>
                                    <div class="control">
                                        <input class="input" type="email" name="email" required>
                                    </div>
                                </div>

                                <div class="field">
                                    <label class="label">{{ __('app.register_password') }}</label>
                                    <div class="control">
                                        <input class="input" type="password" name="password" required>
                                    </div>
                                </div>

                                <div class="field">
                                    <label class="label">{{ __('app.register_password_confirmation') }}</label>
                                    <div class="control">
                                        <input class="input" type="password" name="password_confirmation" required>
                                    </div>
                                </div>

                                <div class="field">
                                    <label class="label">Captcha: {{ $captcha[0] }} + {{ $captcha[1] }} = ?</label>
                                    <div class="control">
                                        <input class="input" type="text" name="captcha" required>
                                    </div>
                                </div>

                                <div class="field">
                                    {!! \App\AppModel::getRegInfo()  !!}
                                </div>
                            </form>
                        </section>
                        <footer class="modal-card-foot is-stretched">
                        <span>
                            <button class="button is-success" onclick="document.getElementById('regform').submit();">{{ __('app.register') }}</button>
                        </span>
                        </footer>
                    </div>
                </div>

                <div class="modal" :class="{'is-active': bShowRecover}">
                    <div class="modal-background"></div>
                    <div class="modal-card">
                        <header class="modal-card-head is-stretched">
                            <p class="modal-card-title">{{ __('app.recover_password') }}</p>
                            <button class="delete" aria-label="close" onclick="vue.bShowRecover = false;"></button>
                        </header>
                        <section class="modal-card-body is-stretched">
                            <form method="POST" action="{{ url('/recover') }}" id="formResetPw">
                                @csrf

                                <div class="field">
                                    <label class="label">{{ __('app.email') }}</label>
                                    <div class="control">
                                        <input type="email" onkeyup="javascript:invalidRecoverEmail()" onchange="javascript:invalidRecoverEmail()" onkeydown="if (event.keyCode === 13) { document.getElementById('formResetPw').submit(); }" class="input" name="email" id="recoveremail" required>
                                    </div>
                                </div>

                                <input type="submit" id="recoverpwsubmit" class="is-hidden">
                            </form>
                        </section>
                        <footer class="modal-card-foot is-stretched">
                            <button class="button is-success" onclick="document.getElementById('recoverpwsubmit').click();">{{ __('app.recover_password') }}</button>
                            <button class="button" onclick="vue.bShowRecover = false;">{{ __('app.cancel') }}</button>
                        </footer>
                    </div>
                </div>
            </div>

            <nav class="navbar is-fixed-bottom">
                <div class="home-bottombar has-text-centered is-uppercase">
                    &copy; {{ date('Y') }} {{ env('APP_PROJECTNAME') }} | @if (env('TWITTER_NEWS', null) !== null) <a href="{{ url('/news') }}" target="_blank">{{ __('app.news') }}</a>&nbsp;&nbsp;@endif<a href="{{ url('/about') }}" target="_blank">{{ __('app.about') }}</a>&nbsp;&nbsp;<a href="{{ url('/faq') }}" target="_blank">{{ __('app.faq') }}</a>&nbsp;&nbsp;<a href="{{ url('/tos') }}" target="_blank">{{ __('app.tos') }}</a>&nbsp;&nbsp;<a href="{{ url('/imprint') }}" target="_blank">{{ __('app.imprint') }}</a>&nbsp;&nbsp;@if (env('HELPREALM_WORKSPACE', null) !== null) <a href="{{ url('/contact') }}" target="_blank">{{ __('app.contact') }}</a> @endif
                </div>
            </nav>
        </div>
    </body>

    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        window.vue.handleCookieConsent();
    </script>
</html>

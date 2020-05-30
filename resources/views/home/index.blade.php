{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

@extends('layouts.layout_home')

@section('title', env('APP_TITLE'))

@section('body')
    <div class="column is-2 is-sidespacing"></div>
    <div class="column is-4">
        <div class="home-userarea mobile-margin-padding">
            <a name="login"></a>

            <div class="has-text-centered">
                <h1><strong>{{ \App\AppModel::getNameParts()[0] }}</strong>{{ \App\AppModel::getNameParts()[1] }}</h1>
            </div>

            <form method="POST" action="{{ url('/login') }}">
                @csrf

                <div class="field">
                    <label class="label">{{ __('app.email') }}</label>
                    <div class="control has-icons-left has-icons-right">
                        <input class="input" type="email" name="email" placeholder="name@domain.com">
                        <span class="icon is-small is-left">
                            <i class="fas fa-envelope"></i>
                        </span>
                    </div>
                </div>

                <div class="field">
                    <label class="label">{{ __('app.password') }}</label>
                    <div class="control">
                        <input class="input" type="password" name="password" placeholder="{{ __('app.password') }}">
                    </div>
                </div>

                <div class="home-userarea-login">
                    <div class="field is-inline-block">
                        <div class="control">
                            <button class="button is-link">{{ __('app.login') }}</button>
                        </div>
                    </div>

                    <div class="home-userarea-recover">
                        <a href="javascript:void(0)" onclick="window.vue.bShowRecover = true">{{ __('app.recover_password') }}</a>
                    </div>
                </div>
            </form>

            <div class="home-userarea-divisor">
                <hr/>
            </div>

            <div class="home-userarea-register">
                <button type="button" class="button is-primary" onclick="window.vue.bShowRegister = true">{{ __('app.register') }}</button>
            </div>
        </div>
    </div>

    <div class="column is-4">
        <div class="mobile-margin-padding">
            {!! $index_content !!}
        </div>
    </div>
    <div class="column is-2 is-sidespacing"></div>

    <div class="column is-2 is-sidespacing"></div>
    <div class="column is-8 is-last-column">
        <div class="tagcloud">
            <div class="tagcloud-title">{{ __('app.popular_tags') }}</div>

            <div class="tagcloud-content is-wordbreak">
                @foreach ($taglist as $tag)
                    <a href="#login">#{{ $tag->tag }}</a>
                @endforeach
            </div>
        </div>
    </div>
    <div class="column is-2 is-sidespacing"></div>
@endsection

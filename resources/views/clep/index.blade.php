{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

@extends('layouts.layout_clep')

@section('content')
    <div>
        <h1 class="clep-headline">
            <center>
                @if (strlen(\App\AppModel::getFormattedProjectName()) > 0)
                    {!! \App\AppModel::getFormattedProjectName() !!}
                @else
                    <h1><strong>{{ \App\AppModel::getNameParts()[0] }}</strong>{{ \App\AppModel::getNameParts()[1] }}</h1>
                @endif
            </center>
        </h1>

        @if (strlen(\App\AppModel::getWelcomeContent()) > 0)
            <div class="clep-welcome-content">
                <center>{!! \App\AppModel::getWelcomeContent() !!}</center>
            </div>
        @endif

        <form id="loginform" method="POST" action="{{ url('/login') }}">
            @csrf

            <div class="field">
                <label class="label">{{ __('app.email') }}</label>
                <p class="control has-icons-left has-icons-right">
                    <input class="input" onkeyup="javascript:vue.invalidLoginEmail()" onchange="javascript:vue.invalidLoginEmail()" onkeydown="if (event.keyCode === 13) { document.getElementById('loginform').submit(); }" type="email" name="email" id="loginemail" placeholder="{{ __('app.email') }}" required>
                    <span class="icon is-small is-left">
                        <i class="fas fa-envelope"></i>
                    </span>
                </p>
            </div>

            <div class="field">
                <label class="label">{{ __('app.password') }}</label>
                <p class="control has-icons-left">
                    <input class="input" onkeyup="javascript:vue.invalidLoginPassword()" onchange="javascript:vue.invalidLoginPassword()" onkeydown="if (event.keyCode === 13) { document.getElementById('loginform').submit(); }" type="password" name="password" id="loginpw" placeholder="{{ __('app.password') }}" required>
                    <span class="icon is-small is-left">
                        <i class="fas fa-lock"></i>
                    </span>
                </p>
            </div>

            <div>
                <span>
                    <button class="button is-success" onclick="vue.setclepFlag(); document.getElementById('loginform').submit();">{{ __('app.login') }}</button>
                </span>

                <span class="is-right clep-recover-top">
                    <div class="recover-pw">
                        <center><a href="javascript:void(0)" onclick="vue.bShowRecover = true;">{{ __('app.recover_password') }}</a></center>
                    </div>
                </span>
            </div>

            <div class="clep-border clep-signup">
                <center><a href="javascript:void(0)" onclick="vue.bShowRegister = true;">{{ __('app.register') }}</a></center>
            </div>

            @if (env('APP_PUBLICFEED'))
            <div class="clep-continue-without-account">
                <center><a href="{{ url('/feed') }}" onclick="vue.setclepFlag();">{{ __('app.continue_without_account') }}</a></center>
            </div>
            @endif
        </form>
    </div>
@endsection

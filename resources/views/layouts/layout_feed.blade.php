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

        @if (isset($meta_description))
            <meta name="description" content="{{ $meta_description }}">
        @else
            <meta name="description" content="{{ env('APP_DESCRIPTION') }}">
        @endif

        @if (isset($meta_tags))
            <meta name="tags" content="{{ $meta_tags }}">
        @else
            <meta name="tags" content="{{ env('APP_TAGS') }}">
        @endif

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" type="text/css" href="{{ asset('css/bulma.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/metro-all.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/quill.core.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/quill.snow.css') }}">
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
        <script src="{{ asset('js/axios.min.js') }}"></script>
        <script src="{{ asset('js/quill.min.js') }}"></script>
        <script src="https://js.stripe.com/v3/"></script>

        <title>@yield('title')</title>

        @if (strlen(\App\AppModel::getHeadCode()) > 0)
            {!! \App\AppModel::getHeadCode() !!}
        @endif
    </head>

    <body class="is-member-background">
        <nav class="navbar has-border-bottom is-fixed-top" role="navigation" aria-label="main navigation">
            <div class="navbar-brand">
                <a class="navbar-item is-app-title" href="{{ url('/') }}">
                    @if (strlen(\App\AppModel::getFormattedProjectName()) > 0)
                        {!! \App\AppModel::getFormattedProjectName() !!}
                    @else
                        <strong>{{ \App\AppModel::getNameParts()[0] }}</strong>{{ \App\AppModel::getNameParts()[1] }}
                    @endif
                </a>

                <a id="navbarBurger" role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarMenu" onclick="window.menuVisible = !document.getElementById('navbarMenu').classList.contains('is-active');">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span id="burger-notification"></span>
                </a>
            </div>

            <div id="navbarMenu" class="navbar-menu">
                <div class="navbar-start"></div>

                <center>
                    <div class="field navbar-search is-margin-bottom-small-screen-size">
                        <p class="control has-icons-right">
                            <input type="text" name="hashtag" placeholder="{{ __('app.search_jump_to_hashtag') }}" onkeypress="if (event.which === 13) location.href='{{ url('/t') }}/' + this.value.replace('#', '');">
                            <span class="icon is-small is-right is-top-navbar">
                                <i class="fas fa-search"></i>
                            </span>
                        </p>
                    </div>
                </center>

                <div class="navbar-end">
                    @guest
                        <div class="navbar-item">
                            <div class="buttons">
                                <a class="button is-primary is-bold" href="javascript:void(0);" onclick="vue.bShowRegister = true; if (window.menuVisible) { document.getElementById('navbarMenu').classList.remove('is-active'); document.getElementById('navbarBurger').classList.remove('is-active'); }">
                                    {{ __('app.register') }}
                                </a>
                                <a class="button is-light" href="javascript:void(0);" onclick="vue.bShowLogin = true; if (window.menuVisible) { document.getElementById('navbarMenu').classList.remove('is-active'); document.getElementById('navbarBurger').classList.remove('is-active'); }">
                                    {{ __('app.login') }}
                                </a>
                            </div>
                        </div>
                    @endguest

                    @auth
                    <div class="navbar-item is-mobile-like-screen-width">
                        <div>
                            <i class="far fa-star fa-lg is-pointer" title="{{ __('app.favorites') }}" onclick="window.toggleOverlay('favorites'); if (window.menuVisible) { document.getElementById('navbarMenu').classList.remove('is-active'); document.getElementById('navbarBurger').classList.remove('is-active'); }"></i>&nbsp;<span class="is-mobile-like-screen-width"><a class="is-color-grey" href="javascript:void(0);" onclick="window.toggleOverlay('favorites'); if (window.menuVisible) { document.getElementById('navbarMenu').classList.remove('is-active'); document.getElementById('navbarBurger').classList.remove('is-active'); }">{{ __('app.favorites') }}</a></span>
                        </div>
                    </div>
                    @endauth

                    <div class="navbar-item is-mobile-like-screen-width">
                        <div>
                            <i class="fas fa-hashtag fa-lg is-pointer" title="{{ __('app.popular_tags') }}" onclick="window.toggleOverlay('popular-tags'); if (window.menuVisible) { document.getElementById('navbarMenu').classList.remove('is-active'); document.getElementById('navbarBurger').classList.remove('is-active'); }"></i>&nbsp;<span class="is-mobile-like-screen-width"><a class="is-color-grey" href="javascript:void(0);" onclick="window.toggleOverlay('popular-tags'); if (window.menuVisible) { document.getElementById('navbarMenu').classList.remove('is-active'); document.getElementById('navbarBurger').classList.remove('is-active'); }">{{ __('app.popular_tags') }}</a></span>
                        </div>
                    </div>

                    @auth
                    <div class="navbar-item">
                        <div>
                            <i class="fas fa-upload fa-lg is-pointer" title="{{ __('app.member_upload') }}" onclick="location.href='{{ url('/upload') }}';"></i>&nbsp;<span class="is-mobile-like-screen-width"><a class="is-color-grey" href="javascript:void(0);" onclick="location.href='{{ url('/upload') }}';">{{ __('app.member_upload') }}</a></span>
                        </div>
                    </div>

                    <div class="navbar-item">
                        <div>
                            <i class="fas fa-globe fa-lg is-pointer" title="{{ __('app.geosearch') }}" onclick="location.href='{{ url('/geosearch') }}';"></i>&nbsp;<span class="is-mobile-like-screen-width"><a class="is-color-grey" href="javascript:void(0);" onclick="location.href='{{ url('/geosearch') }}';">{{ __('app.geosearch') }}</a></span>
                        </div>
                    </div>

                    <div class="navbar-item">
                        <div>
                            <i class="fas fa-users fa-lg is-pointer" title="{{ __('app.profile_search') }}" onclick="location.href='{{ url('/profilesearch') }}';"></i>&nbsp;<span class="is-mobile-like-screen-width"><a class="is-color-grey" href="javascript:void(0);" onclick="location.href='{{ url('/profilesearch') }}';">{{ __('app.profile_search') }}</a></span>
                        </div>
                    </div>

                    <div class="navbar-item">
                        <div>
                            <i class="far fa-comment fa-lg is-pointer" title="{{ __('app.messages') }}" onclick="location.href='{{ url('/messages') }}';"></i>&nbsp;<span class="is-mobile-like-screen-width"><a class="is-color-grey" href="javascript:void(0);" onclick="location.href='{{ url('/messages') }}';">{{ __('app.messages') }}</a></span>
                        </div>
                    </div>

                    <div class="navbar-item">
                        <div>
                            <i id="notification-indicator" class="far fa-heart fa-lg is-pointer" onclick="clearPushIndicator(this, document.getElementById('burger-notification')); toggleNotifications('notifications'); if (window.menuVisible) {document.getElementById('navbarMenu').classList.remove('is-active'); document.getElementById('navbarBurger').classList.remove('is-active'); }" title="{{ __('app.notifications') }}"></i>&nbsp;<span class="is-mobile-like-screen-width"><a class="is-color-grey" href="javascript:void(0);" onclick="clearPushIndicator(this, document.getElementById('burger-notification')); toggleNotifications('notifications'); if (window.menuVisible) {document.getElementById('navbarMenu').classList.remove('is-active'); document.getElementById('navbarBurger').classList.remove('is-active'); }">{{ __('app.notifications') }}</a></span>
                        </div>
                    </div>

                    <div class="navbar-item">
                        <div>
                            <img class="avatar is-pointer" src="{{ asset('gfx/avatars/' . $user->avatar) }}" title="{{ __('app.profile') }}"  onclick="location.href='{{ url('/profile') }}';">&nbsp;<span class="is-mobile-like-screen-width"><a class="is-color-grey" href="javascript:void(0);" onclick="location.href='{{ url('/profile') }}';">{{ __('app.profile') }}</a></span>
                        </div>
                    </div>

                    @if ($user->maintainer)
                    <div class="navbar-item">
                        <div>
                            <i class="fas fa-tools is-pointer" title="{{ __('app.maintainer_area') }}"  onclick="location.href='{{ url('/maintainer') }}';"></i>&nbsp;<span class="is-mobile-like-screen-width"><a class="is-color-grey" href="javascript:void(0);" onclick="location.href='{{ url('/maintainer') }}';">{{ __('app.maintainer_area') }}</a></span>
                        </div>
                    </div>
                    @endif

                    <div class="navbar-item">
                        <div>
                            <i class="fas fa-sign-out-alt fa-lg is-pointer" title="{{ __('app.logout') }}"  onclick="location.href='{{ url('/logout') }}';"></i>&nbsp;<span class="is-mobile-like-screen-width"><a class="is-color-grey" href="javascript:void(0);" onclick="location.href='{{ url('/logout') }}';">{{ __('app.logout') }}</a></span>
                        </div>
                    </div>
                    @endauth

                    <div class="navbar-item has-dropdown is-hoverable is-menu-project-related-links is-mobile-like-screen-width">
                        <a class="navbar-link" href="javascript:void(0);" onclick="document.getElementById('navbar-dropdown-links').classList.toggle('is-hidden');">
                            {{ __('app.more') }}
                        </a>

                        <div class="navbar-dropdown is-menu-project-related-links is-hidden" id="navbar-dropdown-links">
                            @if (env('TWITTER_NEWS', null) !== null)
                                <a class="navbar-item" class="navbar-item" href="{{ url('/news') }}">{{ __('app.news') }}</a>
                            @endif

                            <a class="navbar-item" href="{{ url('/about') }}">{{ __('app.about') }}</a>

                            <a class="navbar-item" href="{{ url('/faq') }}">{{ __('app.faq') }}</a>

                            <a class="navbar-item" href="{{ url('/tos') }}">{{ __('app.tos') }}</a>

                            <a class="navbar-item" href="{{ url('/imprint') }}">{{ __('app.imprint') }}</a>

                            @if (env('HELPREALM_WORKSPACE', null) !== null)
                                <a class="navbar-item" href="{{ url('/contact') }}">{{ __('app.contact') }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        @if (env('APP_PUBLICFEED'))
            <div id="cookie-consent" class="cookie-consent has-text-centered is-top-53">
                <div class="cookie-consent-inner">
                    {!! $cookie_consent !!}
                </div>

                <div class="cookie-consent-button">
                    <a class="is-color-grey" href="javascript:void(0)" onclick="window.vue.clickedCookieConsentButton()">{{ __('app.cookie_consent_close') }}</a>
                </div>
            </div>
        @endif

        <div id="main" class="container">
            <div class="notifications" id="notifications">
                <div>
                    <div class="is-inline-block"></div>
                    <div class="is-inline-block float-right notification-close-icon" onclick="clearPushIndicator(this, document.getElementById('burger-notification')); toggleNotifications('notifications'); if (window.menuVisible) {document.getElementById('navbarMenu').classList.remove('is-active'); document.getElementById('navbarBurger').classList.remove('is-active'); }"><i class="fas fa-times is-pointer"></i></div>
                </div>

                <div class="notifications-content" id="notification-content"></div>
            </div>

            <div class="overlay-list" id="overlay-favorites">
                <div class="overlay-list-content">
                    @include('widgets.favorites', ['favorites' => \App\FavoritesModel::getDetailedForUser(auth()->id()), 'inoverlay' => true])
                </div>
            </div>

            <div class="overlay-list" id="overlay-popular-tags">
                <div class="overlay-list-content">
                    @include('widgets.populartags', ['taglist' => \App\TagsModel::getPopularTags(), 'inoverlay' => true])
                </div>
            </div>

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
                    <br/>
                </div>
            @endif

            @if (Session::has('error'))
                <div id="error-message-2" class="is-z-index-3">
                    <article class="message is-danger">
                        <div class="message-header">
                            <p>{{ __('app.error') }}</p>
                            <button class="delete" aria-label="delete" onclick="document.getElementById('error-message-2').style.display = 'none';"></button>
                        </div>
                        <div class="message-body">
                            {!! Session::get('error') !!}
                        </div>
                    </article>
                    <br/>
                </div>
            @endif

            <div class="flash is-flash-error" id="flash-error">
                <p id="flash-error-content">
                    @if (Session::has('flash.error'))
                        {!! Session::get('flash.error') !!}
                    @endif
                </p>
            </div>

            <div class="flash is-flash-success" id="flash-success">
                <p id="flash-success-content">
                    @if (Session::has('flash.success'))
                        {!! Session::get('flash.success') !!}
                    @endif
                </p>
            </div>

            @if (Session::has('notice'))
                <div id="notice-message" class="is-z-index-3">
                    <article class="message is-info">
                        <div class="message-header">
                            <p>{{ __('app.notice') }}</p>
                            <button class="delete" aria-label="delete" onclick="document.getElementById('notice-message').style.display = 'none';"></button>
                        </div>
                        <div class="message-body">
                            {!! Session::get('notice') !!}
                        </div>
                    </article>
                    <br/>
                </div>
            @endif

            @if (Session::has('success'))
                <div id="success-message" class="is-z-index-3">
                    <article class="message is-success">
                        <div class="message-header">
                            <p>{{ __('app.success') }}</p>
                            <button class="delete" aria-label="delete" onclick="document.getElementById('success-message').style.display = 'none';"></button>
                        </div>
                        <div class="message-body">
                            {!! Session::get('success') !!}
                        </div>
                    </article>
                    <br/>
                </div>
            @endif

            <div class="columns is-vcentered is-multiline">
				@include('widgets.populartags')

                @auth
                    @include('widgets.stories')
                    @include('widgets.favorites', ['favorites' => \App\FavoritesModel::getDetailedForUser(auth()->id())])
                @endauth

                @yield('body')
            </div>

            @auth
            <div class="modal" :class="{'is-active': bShowEditProfile}">
                <div class="modal-background"></div>
                <div class="modal-card is-top-25">
                    <header class="modal-card-head is-stretched">
                        <p class="modal-card-title">{{ __('app.edit_profile') }}</p>
                        <button class="delete" aria-label="close" onclick="vue.bShowEditProfile = false;"></button>
                    </header>
                    <section class="modal-card-body is-stretched">
                        <form method="POST" action="{{ url('/profile/edit') }}" id="formEditProfile" enctype="multipart/form-data">
                            @csrf

                            @if (env('STRIPE_ENABLE') === true)
                                @if ((!Auth::guest()) && (!\App\User::get(auth()->id())->pro))
                                    <div class="field">
                                        <div class="control">
                                            <a href="javascript:void(0)" onclick="window.vue.bShowBuyProMode = true; window.vue.bShowEditProfile = false;" class="button is-success">{{ __('app.purchase_pro_mode') }}</a>
                                        </div>
                                    </div>

                                    <hr/>
                                @endif
						    @endif

                            <div class="field is-stretched">
                                <label class="label">{{ __('app.avatar') }}</label>
                                <div class="settings-avatar-image"><img src="{{ asset('gfx/avatars/' . $user->avatar) }}"></div>
                                <div class="settings-avatar-input"><input type="file" name="avatar" data-role="file" data-button-title="<i class='far fa-folder'></i>"></div>
                            </div>

                            <div class="field">
                                <label class="label">{{ __('app.username') }}</label>
                                <div class="control">
                                    <input type="text" name="username" value="{{ $user->username }}">
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">{{ __('app.bio') }}</label>
                                <div class="control">
                                    <textarea name="bio">{{ $user->bio }}</textarea>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">{{ __('app.gender') }}</label>
                                <div class="control">
                                    <select name="gender">
                                        <option value="0" @if ($user->gender === 0) {{ 'selected' }} @endif>{{ __('app.gender_unspecified') }}</option>
                                        <option value="1" @if ($user->gender === 1) {{ 'selected' }} @endif>{{ __('app.gender_male') }}</option>
                                        <option value="2" @if ($user->gender === 2) {{ 'selected' }} @endif>{{ __('app.gender_female') }}</option>
                                        <option value="3" @if ($user->gender === 3) {{ 'selected' }} @endif>{{ __('app.gender_diverse') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">{{ __('app.birthday') }}</label>
                                <div class="control">
                                    <input type="date" class="input" name="birthday" value="{{ date('Y-m-d', strtotime($user->birthday)) }}">
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">{{ __('app.location') }}</label>
                                <div class="control">
                                    <input type="text" name="location" value="{{ $user->location }}">
                                </div>
                            </div>

                            @foreach (\App\ProfileDataModel::queryAll(auth()->id()) as $key => $item)
                                <div class="field">
                                    <label class="label">{{ $item['translation'] }}</label>
                                    <div class="control">
                                        <textarea name="{{ $key }}">{{ $item['content'] }}</textarea>
                                    </div>
                                </div>
                            @endforeach

                            <hr/>

                            <div class="field">
                                <label class="label">{{ __('app.password') }}</label>
                                <div class="control">
                                    <input type="password" name="password">
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">{{ __('app.password_confirm') }}</label>
                                <div class="control">
                                    <input type="password" name="password_confirm">
                                </div>
                            </div>

                            <hr/>

                            <div class="field">
                                <label class="label">{{ __('app.email') }}</label>
                                <div class="control">
                                    <input type="email" name="email" value="{{ $user->email }}">
                                </div>
                            </div>

                            <div class="field">
                                <div class="control">
                                    <input type="checkbox" name="email_on_message" value="1" data-role="checkbox" data-style="2" data-caption="{{ __('app.email_on_message') }}" @if ($user->email_on_message) {{ 'checked' }} @endif>
                                </div>
                            </div>

                            <div class="field">
                                <div class="control">
                                    <input type="checkbox" name="newsletter" value="1" data-role="checkbox" data-style="2" data-caption="{{ __('app.newsletter_setting') }}" @if ($user->newsletter) {{ 'checked' }} @endif>
                                </div>
                            </div>

                            @if (env('APP_ENABLENSFWFILTER'))
                                <div class="field">
                                    <div class="control">
                                        <input type="checkbox" name="nsfw" value="1" data-role="checkbox" data-style="2" data-caption="{{ __('app.nsfw_show') }}" @if ($user->nsfw) {{ 'checked' }} @endif>
                                    </div>
                                </div>
                            @endif

                            <div class="field">
                                <div class="control">
                                    <input type="checkbox" name="geo_exclude" value="1" data-role="checkbox" data-style="2" data-caption="{{ __('app.geo_exclude') }}" @if ($user->geo_exclude) {{ 'checked' }} @endif>
                                </div>
                            </div>

                            <input type="submit" id="editprofilesubmit" class="is-hidden">
                        </form>

                        <hr/>

                        <div class="field">
                            <label class="label">{{ __('app.theme') }}</label>
                            <div class="control">
                                <select id="themes">
                                    <option value="_default">{{ __('app.theme_default') }}</option>
                                    @foreach (\App\ThemeModel::getThemes() as $theme)
                                        <option value="{{ $theme }}" <?php if ((isset($_COOKIE['theme'])) && ($_COOKIE['theme'] == $theme)) { echo 'selected'; } ?>>{{ pathinfo($theme, PATHINFO_FILENAME) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <br/>

                            <button type="button" class="button" onclick="window.setTheme(document.getElementById('themes').value);">{{ __('app.change_theme') }}</button>
                        </div>

                        <hr/>

                        <div class="field">
                            <label class="label">{{ __('app.deactivate_label') }}</label>
                            <div class="control">
                                <input type="button" value="{{ __('app.deactivate') }}" onclick="lockUser({{ auth()->id() }})">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.delete_account_label') }}</label>
                            <div class="control">
                                <input type="button" value="{{ __('app.delete_account') }}" onclick="deleteUserAccount()">
                            </div>
                        </div>

                        <br/>
                    </section>
                    <footer class="modal-card-foot is-stretched">
                        <button class="button is-success" onclick="document.getElementById('editprofilesubmit').click();">{{ __('app.save') }}</button>
                        <button class="button" onclick="vue.bShowEditProfile = false;">{{ __('app.cancel') }}</button>
                    </footer>
                </div>
            </div>

            <div class="modal" :class="{'is-active': bShowEditComment}">
                <div class="modal-background"></div>
                <div class="modal-card">
                    <header class="modal-card-head is-stretched">
                        <p class="modal-card-title">{{ __('app.edit_comment') }}</p>
                        <button class="delete" aria-label="close" onclick="vue.bShowEditComment = false;"></button>
                    </header>
                    <section class="modal-card-body is-stretched">
                        <form id="formResetPw">
                            @csrf

                            <input type="hidden" id="editCommentId" name="comment">

                            <div class="field">
                                <label class="label">{{ __('app.text') }}</label>
                                <div class="control">
                                    <textarea name="text" id="editCommentText"></textarea>
                                </div>
                            </div>

                            <input type="button" id="editcommentsubmit" onclick="editComment(document.getElementById('editCommentId').value, document.getElementById('editCommentText').value); vue.bShowEditComment = false;" class="is-hidden">
                        </form>
                    </section>
                    <footer class="modal-card-foot is-stretched">
                        <button class="button is-success" onclick="document.getElementById('editcommentsubmit').click();">{{ __('app.save') }}</button>
                        <button class="button" onclick="vue.bShowEditComment = false;">{{ __('app.cancel') }}</button>
                    </footer>
                </div>
            </div>

			@if (env('STRIPE_ENABLE') == true)
            <div class="modal" :class="{'is-active': bShowBuyProMode}">
                <div class="modal-background"></div>
                <div class="modal-card">
                    <header class="modal-card-head is-stretched">
                        <p class="modal-card-title">{{ __('app.buy_pro_mode_title') }}</p>
                        <button class="delete" aria-label="close" onclick="vue.bShowBuyProMode = false;"></button>
                    </header>
                    <section class="modal-card-body is-stretched">
                        <div class="field">
                            {!! __('app.buy_pro_mode_info', ['costs' => env('STRIPE_COSTS_LABEL')]) !!}
                        </div>

                        <form action="{{ url('/payment/charge') }}" method="post" id="payment-form" class="stripe">
                            @csrf

                            <div class="form-row">
                                <label for="card-element">
                                    {{ __('app.credit_or_debit_card') }}
                                </label>
                                <div id="card-element"></div>

                                <div id="card-errors" role="alert"></div>
                            </div>

                            <br/>

                            <button class="button is-link">{{ __('app.submit_payment') }}</button>
                        </form>
                    </section>
                    <footer class="modal-card-foot is-stretched">
                        <button class="button" onclick="vue.bShowBuyProMode = false;">{{ __('app.close') }}</button>
                    </footer>
                </div>
            </div>
			@endif

            @endauth

            <div class="modal" :class="{'is-active': bShowReplyThread}">
                <div class="modal-background"></div>
                <div class="modal-card">
                    <header class="modal-card-head is-stretched">
                        <p class="modal-card-title">{{ __('app.reply_thread') }}</p>
                        <button class="delete" aria-label="close" onclick="vue.bShowReplyThread = false;"></button>
                    </header>
                    <section class="modal-card-body is-stretched">
                        @auth
                        <form id="formReplyThread">
                            @csrf

                            <input type="hidden" id="thread-reply-parent" name="parent">

                            <div class="field">
                                <label class="label">{{ __('app.text') }}</label>
                                <div class="control">
                                    <textarea name="text" id="thread-reply-textarea"></textarea>
                                </div>
                            </div>

                            <input type="button" id="replythreadsubmit" onclick="replyThread(document.getElementById('thread-reply-parent').value, document.getElementById('thread-reply-textarea').value); vue.bShowReplyThread = false;" class="is-hidden">
                        </form>
                        @elseguest
                            <b>{{ __('app.please_login') }}</b>
                        @endauth
                    </section>
                    <footer class="modal-card-foot is-stretched">
                        @auth
                            <button class="button is-success" onclick="document.getElementById('replythreadsubmit').click();">{{ __('app.submit') }}</button>
                        @endauth
                        <button class="button" onclick="vue.bShowReplyThread = false;">{{ __('app.cancel') }}</button>
                    </footer>
                </div>
            </div>

            @auth
                <div class="modal" :class="{'is-active': bShowAddStory}">
                    <div class="modal-background"></div>
                    <div class="modal-card">
                        <header class="modal-card-head is-stretched">
                            <p class="modal-card-title">{{ __('app.add_story') }}</p>
                            <button class="delete" aria-label="close" onclick="vue.bShowAddStory = false;"></button>
                        </header>
                        <section class="modal-card-body is-stretched">
                            <ul data-role="tabs" data-expand="true">
                                <li><a href="#tab-page-1" onclick="window.addStoryTabPage = 1">{{ __('app.story_image') }}</a></li>
                                <li><a href="#tab-page-2" onclick="window.addStoryTabPage = 2">{{ __('app.story_text') }}</a></li>
                            </ul>
                            <div class="border bd-default no-border-top p-2">
                                <div id="tab-page-1">
                                    <div>
                                        <div class="field">
                                            <div class="control">
                                                <input id="story-add-file-file" type="file" data-role="file" data-type="2" oninput="window.setStoryImage(this);">
                                                <input type="hidden" id="story-add-file-name">
                                            </div>
                                        </div>

                                        <div class="field">
                                            <label class="label">{{ __('app.story_message') }}</label>
                                            <div class="control">
                                                <textarea id="story-add-file-text" oninput="document.getElementById('add-story-message').innerHTML = this.value;"></textarea>
                                            </div>
                                        </div>

                                        <div class="field is-inline-block is-margin-right-15">
                                            <label class="label">{{ __('app.story_text_color') }}</label>
                                            <div class="control">
                                                <input id="story-add-file-color" type="color" value="#000000" onchange="document.getElementById('add-story-message').style.color = this.value;">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="tab-page-2">
                                    <div>
                                        <div class="field">
                                            <label class="label">{{ __('app.story_message') }}</label>
                                            <div class="control">
                                                <textarea id="story-add-message-text" oninput="document.getElementById('add-story-message').innerHTML = this.value;"></textarea>
                                            </div>
                                        </div>

                                        <div class="field is-inline-block is-margin-right-15">
                                            <label class="label">{{ __('app.story_text_color') }}</label>
                                            <div class="control">
                                                <input id="story-add-message-color" type="color" value="#000000" onchange="document.getElementById('add-story-message').style.color = this.value;">
                                            </div>
                                        </div>

                                        <div class="field is-inline-block">
                                            <label class="label">{{ __('app.story_bg_color') }}</label>
                                            <div class="control">
                                                <input id="story-add-message-bgcolor" type="color" value="#ffffff" onchange="document.getElementById('add-story-content').style.backgroundColor = this.value;">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="add-story-content">
                                    <div id="add-story-message"></div>
                                </div>
                            </div>
                        </section>
                        <footer class="modal-card-foot is-stretched">
                            <button class="button is-success" onclick="window.postStory();">{{ __('app.submit') }}</button>
                            <button class="button" onclick="vue.bShowAddStory = false;">{{ __('app.cancel') }}</button>
                        </footer>
                    </div>
                </div>

                <div class="modal" :class="{'is-active': bShowViewStory}">
                    <div class="modal-background"></div>
                    <div class="modal-card">
                        <header class="modal-card-head is-stretched">
                            <p class="modal-card-title" id="story-title"></p>
                            <button class="delete" aria-label="close" onclick="vue.bShowViewStory = false;"></button>
                        </header>
                        <section class="modal-card-body is-stretched">
                            <div>
                                <div id="story-content"><div id="story-message"></div></div>
                            </div>

                            <div>
                                <i class="fas fa-arrow-left is-pointer" onclick="if (window.currentStoryIndex > 0) window.currentStoryIndex--; showStoryPost(window.currentStoryIndex);"></i>&nbsp;&nbsp;
                                <i class="fas fa-arrow-right is-pointer" onclick="if (window.currentStoryIndex < window.currentStoryData.length - 1) window.currentStoryIndex++; showStoryPost(window.currentStoryIndex);"></i>
                            </div>
                        </section>
                        <footer></footer>
                    </div>
                </div>
            @endauth

            @if (strlen(\App\AppModel::getWelcomeContent()) > 0)
                <div class="modal" :class="{'is-active': bShowWelcomeOverlay}">
                    <div class="modal-background is-almost-not-transparent"></div>
                    <div class="modal-card">
                        <header class="modal-card-head is-stretched">
                            <p class="modal-card-title">{{ env('APP_PROJECTNAME') }}</p>
                            <button class="delete" aria-label="close" onclick="vue.bShowWelcomeOverlay = false;"></button>
                        </header>
                        <section class="modal-card-body is-stretched">
                            {!! \App\AppModel::getWelcomeContent() !!}
                        </section>
                        <footer class="modal-card-foot is-stretched">
                            <button class="button is-success" onclick="vue.markWelcomeOverlayRead();">{{ __('app.continue') }}</button>
                        </footer>
                    </div>
                </div>
            @endif

            @guest
                @include('layouts.layout_guest')
            @endguest
        </div>
    </body>

    <script src="{{ asset('js/app.js') }}"></script>
    @yield('javascript')
    <script>
        window.pushClientNotification = function(msg) {
            Push.create('{{ env('APP_PROJECTNAME') }}', {
                body: msg,
                icon: '{{ asset('logo.png') }}',
                timeout: 4000,
                onClick: function () {
                    window.focus();
                    this.close();
                }
            });
        };

        window.transferGeolocation = function(geodata) {
            let latitude = geodata.coords.latitude;
            let longitude = geodata.coords.longitude;

            window.vue.ajaxRequest('post', '{{ url('/profile/geo') }}', { latitude: latitude, longitude: longitude }, function(response) { 
                if (response.code == 500) {
                    console.log(response.msg);
                }
            });
        };

        window.queryGeoLocation = function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(window.transferGeolocation);
            }
        };

        window.fetchNotifications = function() {
            window.vue.ajaxRequest('get', '{{ url('/notifications/list') }}', {}, function(response){
                if (response.code === 200) {
                    if (response.data.length > 0) {
                        let noyet = document.getElementById('no-notifications-yet');
                        if (noyet) {
                            noyet.remove();
                        }

                        let indicator = document.getElementById('notification-indicator');
                        if (indicator) {
                            indicator.classList.remove('far');
                            indicator.classList.add('fas', 'is-hearted');
                            indicator.setAttribute('title', response.data.length + ' new notifications');
                        }

                        let burgerSpan = document.getElementById('burger-notification');
                        if (burgerSpan) {
                            burgerSpan.style.display = 'unset';
                        }

                        response.data.forEach(function(elem, index) {
                            @if (isset($_GET['clep_push_handler']))
                                window['{{ $_GET['clep_push_handler'] }}'](elem.shortMsg, elem.longMsg);
                            @else
                                window.pushClientNotification(elem.shortMsg);
                            @endif

                            let html = renderNotification(elem, true);
                            document.getElementById('notification-content').innerHTML = html + document.getElementById('notification-content').innerHTML;
                        });
                    }
                }
            });

            setTimeout('fetchNotifications()', 50000);
        };

        window.notificationPagination = null;
        window.fetchNotificationList = function() {
            document.getElementById('notification-content').innerHTML += '<center><i id="notification-spinner" class="fas fa-spinner fa-spin"></i></center>';

            let loader = document.getElementById('load-more-notifications');
            if (loader) {
                loader.remove();
            }

            window.vue.ajaxRequest('get', '{{ url('/notifications/fetch') }}' + ((window.notificationPagination) ? '?paginate=' + window.notificationPagination : ''), {}, function(response) {
                if (response.code === 200) {
                    if (response.data.length > 0) {
                        let noyet = document.getElementById('no-notifications-yet');
                        if (noyet) {
                            noyet.remove();
                        }

                        response.data.forEach(function(elem, index) {
                            let html = renderNotification(elem);

                            document.getElementById('notification-content').innerHTML += html;
                        });

                        window.notificationPagination = response.data[response.data.length-1].id;

                        document.getElementById('notification-content').innerHTML += '<center><i id="load-more-notifications" class="fas fa-arrow-down is-pointer" onclick="fetchNotificationList()"></i></center>';
                        document.getElementById('notification-spinner').remove();
                    } else {
                        if (window.notificationPagination === null) {
                            document.getElementById('notification-content').innerHTML = '<div id="no-notifications-yet"><center><i>{{ __('app.no_notifications_yet') }}</i></center></div>';
                        }

                        let loader = document.getElementById('load-more-notifications');
                        if (loader) {
                            loader.remove();
                        }

                        let spinner = document.getElementById('notification-spinner');
                        if (spinner) {
                            spinner.remove();
                        }
                    }
                }
            });
        };

        const stripeTokenHandler = (token) => {
            const form = document.getElementById('payment-form');
            const hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);
            form.submit();
        }

        document.addEventListener('DOMContentLoaded', () => {
            @auth
            setTimeout('fetchNotifications()', 5000);
            setTimeout('fetchNotificationList()', 100);
            @endauth

            window.vue.translationTable.copiedToClipboard = '{{ __('app.copiedToClipboard') }}';
            window.vue.translationTable.toggleNsfw = '{{ __('app.toggleNsfw') }}';
            window.vue.translationTable.toggleNsfw2 = '{{ __('app.toggleNsfw2') }}';
            window.vue.translationTable.lock = '{{ __('app.lock') }}';
            window.vue.translationTable.edit = '{{ __('app.edit') }}';
            window.vue.translationTable.shareWhatsApp = '{{ __('app.shareWhatsApp') }}';
            window.vue.translationTable.shareTwitter = '{{ __('app.shareTwitter') }}';
            window.vue.translationTable.shareFacebook = '{{ __('app.shareFacebook') }}';
            window.vue.translationTable.shareEMail = '{{ __('app.shareEMail') }}';
            window.vue.translationTable.shareSms = '{{ __('app.shareSms') }}';
            window.vue.translationTable.copyLink = '{{ __('app.copyLink') }}';
            window.vue.translationTable.report = '{{ __('app.report') }}';
            window.vue.translationTable.expandThread = '{{ __('app.expandThread') }}';
            window.vue.translationTable.reply = '{{ __('app.reply') }}';
            window.vue.translationTable.viewMore = '{{ __('app.viewMore') }}';
            window.vue.translationTable.reportPost = '{{ __('app.reportPost') }}';
            window.vue.translationTable.removeFav = '{{ __('app.removeFav') }}';
            window.vue.translationTable.addFav = '{{ __('app.addFav') }}';
            window.vue.translationTable.noFavsYet = '{{ __('app.noFavsYet') }}';
            window.vue.translationTable.confirmLockPost = '{{ __('app.confirmLockPost') }}';
            window.vue.translationTable.confirmToggleNsfw = '{{ __('app.confirmToggleNsfw') }}';
            window.vue.translationTable.confirmLockHashtag = '{{ __('app.confirmLockHashtag') }}';
            window.vue.translationTable.confirmLockUser = '{{ __('app.confirmLockUser') }}';
            window.vue.translationTable.confirmDeleteOwnAccount = '{{ __('app.confirmDeleteOwnAccount') }}';
            window.vue.translationTable.confirmLockComment = '{{ __('app.confirmLockComment') }}';

            window.vue.handleCookieConsent();

            @if (strlen(\App\AppModel::getWelcomeContent()) > 0)
                window.vue.handleWelcomeOverlay();
            @endif

            @auth
                fetchStorySelection();
            @endauth

            @auth
                window.geoLoopTransmission = function() {
                    window.queryGeoLocation();

                    setTimeout('window.geoLoopTransmission()', 1000 * 150)
                }

                @if ((!isset($_GET['clep_geo'])) || ($_GET['clep_geo'] == 0))
                    setTimeout('window.geoLoopTransmission()', 2500);
                @endif
            @endauth

            @guest
                @if (env('APP_PUBLICFEED'))
                    document.getElementsByClassName('fixed-frame')[0].style.top = '76px';
                @endif
            @endguest

            window.addStoryTabPage = 1;

            window.menuVisible = false;

            const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

            if ($navbarBurgers.length > 0) {
                $navbarBurgers.forEach( el => {
                    el.addEventListener('click', () => {
                        const target = el.dataset.target;
                        const $target = document.getElementById(target);

                        el.classList.toggle('is-active');
                        $target.classList.toggle('is-active');

                    });
                });
            }

            @if (Session::has('flash.error'))
                setTimeout('window.vue.showError()', 500);
            @endif

            @if (Session::has('flash.success'))
                setTimeout('window.vue.showSuccess()', 500);
            @endif

			@if (env('STRIPE_ENABLE') == true)
				var stripe = Stripe('{{ env('STRIPE_TOKEN_PUBLIC') }}');
				var elements = stripe.elements();

				const style = {
					base: {
						fontSize: '16px',
						color: '#32325d',
					},
				};

				const card = elements.create('card', {style});
				card.mount('#card-element');

				const form = document.getElementById('payment-form');
				form.addEventListener('submit', async (event) => {
					event.preventDefault();

					const {token, error} = await stripe.createToken(card);

					if (error) {
						const errorElement = document.getElementById('card-errors');
						errorElement.textContent = error.message;
					} else {
						stripeTokenHandler(token);
					}
				});
			@endif
        });
    </script>
</html>

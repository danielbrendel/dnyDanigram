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
        <link rel="stylesheet" type="text/css" href="{{ \App\ThemeModel::getThemeToInclude() }}">


        <link rel="shortcut icon" type="image/png" href="{{ asset('/favicon.png') }}">

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
                            <i class="far fa-comment fa-lg is-pointer" title="{{ __('app.messages') }}" onclick="location.href='{{ url('/messages') }}';"></i>&nbsp;<span class="is-mobile-like-screen-width"><a class="is-color-grey" href="javascript:void(0);" onclick="location.href='{{ url('/messages') }}';">{{ __('app.messages') }}</a></span>
                        </div>
                    </div>

                    <div class="navbar-item">
                        <div>
                            <i id="notification-indicator" class="far fa-heart fa-lg is-pointer" onclick="clearPushIndicator(this); toggleNotifications('notifications'); if (window.menuVisible) {document.getElementById('navbarMenu').classList.remove('is-active'); document.getElementById('navbarBurger').classList.remove('is-active'); }" title="{{ __('app.notifications') }}"></i>&nbsp;<span class="is-mobile-like-screen-width"><a class="is-color-grey" href="javascript:void(0);" onclick="clearPushIndicator(this); toggleNotifications('notifications'); if (window.menuVisible) {document.getElementById('navbarMenu').classList.remove('is-active'); document.getElementById('navbarBurger').classList.remove('is-active'); }">{{ __('app.notifications') }}</a></span>
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
                    {{ $cookie_consent }}
                </div>

                <div class="cookie-consent-button">
                    <a class="is-color-grey" href="javascript:void(0)" onclick="window.vue.clickedCookieConsentButton()">{{ __('app.cookie_consent_close') }}</a>
                </div>
            </div>
        @endif

        <div id="main" class="container">
            <div class="notifications" id="notifications">
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
                            {{ Session::get('error') }}
                        </div>
                    </article>
                </div>
            @endif

            <div class="flash is-flash-error" id="flash-error">
                <p id="flash-error-content">
                    @if (Session::has('flash.error'))
                        {{ Session::get('flash.error') }}
                    @endif
                </p>
            </div>

            <div class="flash is-flash-success" id="flash-success">
                <p id="flash-success-content">
                    @if (Session::has('flash.success'))
                        {{ Session::get('flash.success') }}
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

                            <hr/>

                            <div class="field">
                                <label class="label">{{ __('app.password') }}</label>
                                <div class="control">
                                    <input type="text" name="password">
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">{{ __('app.password_confirm') }}</label>
                                <div class="control">
                                    <input type="text" name="password_confirm">
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

                        response.data.forEach(function(elem, index) {
                            Push.create('{{ env('APP_PROJECTNAME') }}', {
                                body: elem.shortMsg,
                                icon: '{{ asset('favicon.png') }}',
                                timeout: 4000,
                                onClick: function () {
                                    window.focus();
                                    this.close();
                                }
                            });

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

        document.addEventListener('DOMContentLoaded', () => {
            @auth
            setTimeout('fetchNotifications()', 5000);
            setTimeout('fetchNotificationList()', 100);
            @endauth

            window.vue.handleCookieConsent();

            @if (strlen(\App\AppModel::getWelcomeContent()) > 0)
                window.vue.handleWelcomeOverlay();
            @endif

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
        });
    </script>
</html>

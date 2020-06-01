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

                <a id="navbarBurger" role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarMenu" onclick="window.menuVisible = !document.getElementById('navbarMenu').classList.contains('is-active');">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </a>
            </div>

            <div id="navbarMenu" class="navbar-menu">
                <div class="navbar-start"></div>

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
                            <i class="far fa-comment fa-lg is-pointer" title="{{ __('app.member_messages') }}" onclick="location.href='{{ url('/messages') }}';"></i>
                        </div>
                    </div>

                    <div class="navbar-item">
                        <div>
                            <i id="notification-indicator" class="far fa-heart fa-lg is-pointer" onclick="clearPushIndicator(this); toggleNotifications('notifications'); if (window.menuVisible) {document.getElementById('navbarMenu').classList.remove('is-active'); document.getElementById('navbarBurger').classList.remove('is-active'); }" title="{{ __('app.notifications') }}"  onclick="location.href='{{ url('/notifications') }}';"></i>
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
            <div class="notifications" id="notifications">
                <div class="notifications-content" id="notification-content"></div>
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

                            <input type="submit" id="editprofilesubmit" class="is-hidden">
                        </form>

                        <hr/>

                        <div class="field">
                            <label class="label">{{ __('app.deactivate_label') }}</label>
                            <div class="control">
                                <input type="button" value="{{ __('app.deactivate') }}" onclick="lockUser({{ auth()->id() }})">
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
        </div>
    </body>

    <script src="{{ asset('js/app.js') }}"></script>
    @yield('javascript')
    <script>
        window.fetchNotifications = function() {
            window.vue.ajaxRequest('get', '{{ url('/notifications/list') }}', {}, function(response){
                if (response.code === 200) {
                    if (response.data.length > 0) {
                        let indicator = document.getElementById('notification-indicator');
                        if (indicator) {
                            indicator.classList.remove('far');
                            indicator.classList.add('fas', 'is-hearted');
                            indicator.setAttribute('title', response.data.length + ' new notifications');
                        }

                        response.data.forEach(function(elem, index) {
                            Push.create('{{ env('APP_NAME') }}', {
                                body: elem.shortMsg,
                                icon: '{{ asset('gfx/logo.png') }}',
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
            setTimeout('fetchNotifications()', 5000);
            setTimeout('fetchNotificationList()', 100);

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

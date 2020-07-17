{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

@extends('layouts.layout_feed')

@section('title', env('APP_PROJECTNAME') . ' - ' . __('app.view_profile', ['name' => $profile->username]))

@section('body')
    <div class="column is-5" id="feed-left">
        <div class="member-form is-default-padding is-mobile-like-screen-width is-top-negative-mobile-like">
            @auth
                @include('widgets.userinfo', ['user' => $profile, 'admin' => $user->admin])
            @elseguest
                @include('widgets.userinfo', ['user' => $profile, 'admin' => false])
            @endauth
        </div>

        <div class="feed-nav is-default-padding">
            <div class="tabs is-member-form-without-border-and-backgroundcolor is-foreground">
                <ul>
                    <li id="linkFetchTop">
                        <a href="javascript:void(0)" onclick="window.vue.setPostFetchType(1); document.getElementById('feed').innerHTML = ''; window.paginate = null; fetchPosts();" class="is-color-grey">{{ __('app.top') }}</a>
                    </li>
                    <li id="linkFetchLatest">
                        <a href="javascript:void(0)" onclick="window.vue.setPostFetchType(2); document.getElementById('feed').innerHTML = ''; window.paginate = null; fetchPosts();" class="is-color-grey">{{ __('app.latest') }}</a>
                    </li>
                </ul>
            </div>
        </div>

        <div id="feed"></div>
        <div id="loading" style="display: none;"><br/><br/><center><i class="fas fa-spinner fa-spin"></i></center></div>
    </div>

    <div class="column is-3 fixed-frame-parent">
        <div class="fixed-frame">
            <div class="member-form is-default-padding">
                @auth
                    @include('widgets.userinfo', ['user' => $profile, 'admin' => $user->admin])
                @elseguest
                    @include('widgets.userinfo', ['user' => $profile, 'admin' => false])
                @endauth
            </div>

            <div class="member-form is-default-padding is-margin-bottom-last-fixed-frame is-member-form-without-border-and-backgroundcolor">
                @include('widgets.company')
            </div>
        </div>
    </div>

    <div class="column is-5 is-sidespacing fa-3x"></div>
@endsection

@section('javascript')
    <script>
        window.onscroll = function(ev) {
            if ((window.scrollY + window.innerHeight) >= document.body.scrollHeight - 10) {
                fetchPosts();
            }
        };

        window.onresize = function() {
            if (window.innerWidth < 1454) {
                document.getElementById('feed-left').classList.remove('is-4');
                document.getElementById('feed-left').classList.add('is-8');
            } else {
                document.getElementById('feed-left').classList.remove('is-8');
                document.getElementById('feed-left').classList.add('is-4');
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            window.paginate = null;

            fetchPosts();
        });

        function fetchPosts()
        {
            if (document.getElementById('user-no-more-posts') !== null) {
                return;
            }

            document.getElementById('loading').style.display = 'block';

            if (window.vue.getPostFetchType() == 1) {
                document.getElementById('linkFetchTop').classList.add('is-active');
                document.getElementById('linkFetchLatest').classList.remove('is-active');
            } else if (window.vue.getPostFetchType() == 2) {
                document.getElementById('linkFetchTop').classList.remove('is-active');
                document.getElementById('linkFetchLatest').classList.add('is-active');
            }

            window.vue.ajaxRequest('GET', '{{ url('/fetch/posts') }}?type=' + window.vue.getPostFetchType() + ((window.paginate !== null) ? '&paginate=' + window.paginate : '') + '&user=' + {{ $profile->id }}, {}, function(response){
                if (response.code == 200) {
                    if (!response.last) {
                        response.data.forEach(function (elem, index) {
                            adminOrOwner = false;

                            @auth
                                adminOrOwner = ({{ $user->admin }}) || ({{ $user->id }} === elem.userId);
                            @endauth

                            let nsfwFlag = 0;

                            @auth
                                nsfwFlag = {{ (int)$user->nsfw }};
                            @endauth

                            let insertHtml = renderPost(elem, adminOrOwner, nsfwFlag, {{ env('APP_ENABLENSFWFILTER') }});

                            document.getElementById('feed').innerHTML += insertHtml;

                            if (window.vue.getPostFetchType() == 1) {
                                window.paginate = response.data[response.data.length - 1].hearts;
                            } else if (window.vue.getPostFetchType() == 2) {
                                window.paginate = response.data[response.data.length - 1].id;
                            }

                            document.getElementById('loading').style.display = 'none';
                        });
                    } else {
                        document.getElementById('feed').innerHTML += '<div id="user-no-more-posts"><br/><br/><center><i>{{ __('app.no_more_posts') }}</i></center><br/></div>';
                        document.getElementById('loading').style.display = 'none';
                    }
                }
            });
        }
    </script>
@endsection

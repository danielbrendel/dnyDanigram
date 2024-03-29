{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2022 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

@extends('layouts.layout_feed')

@section('title', env('APP_PROJECTNAME') . ' - ' . __('app.forum'))

@section('body')
    <div class="column is-5" id="feed-left">
        <div class="is-default-padding-mobile is-mobile-fixed-top">
            <h1>{{ __('app.forum_title') }}</h1>

            <h2>{{ __('app.forum_subtitle') }}</h2>
        </div>

        <div class="is-default-padding-mobile field has-addons">
            <div class="control">
                <input type="text" id="forum-name" onchange="window.forumName = this.value;" placeholder="{{ __('app.search_for_name') }}">
            </div>
            <div class="control">
                <a class="button" href="javascript:void(0);" onclick="window.paginate = null; window.listForums();">{{ __('app.search') }}</a>
            </div>
        </div>

        <div class="field">
            <hr/>
        </div>

        <div id="forums"></div>
    </div>

    <div class="column is-3 fixed-frame-parent">
        <div class="fixed-frame">
            <div class="member-form is-default-padding">
                @auth
                    @include('widgets.userbaseinfo', ['user' => \App\User::getUserBaseInfo(auth()->id())])
                @endauth
            </div>

            <div class="member-form is-default-padding">
                @include('widgets.newusers', ['users' => \App\User::getNewestUsers()])
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
        window.paginate = null;
        window.forumName = '';

        window.listForums = function() {
                if (window.paginate === null) {
                    document.getElementById('forums').innerHTML = '<div id="spinner"><center><i class="fas fa-spinner fa-spin"></i></center></div>';
                } else {
                    document.getElementById('forums').innerHTML += '<div id="spinner"><center><i class="fas fa-spinner fa-spin"></i></center></div>';
                }

                if (document.getElementById('loadmore')) {
                    document.getElementById('loadmore').remove();
                }

                window.vue.ajaxRequest('post', '{{ url('/forum/list') }}', { paginate: window.paginate, name: window.forumName }, function(response){
                    if (response.code == 200) {
                        if (document.getElementById('spinner')) {
                            document.getElementById('spinner').remove();
                        }

                        if (response.data.length > 0) {
                            response.data.forEach(function(elem, index) {
                                let html = window.renderForumItem(elem);

                                document.getElementById('forums').innerHTML += html;
                            });

                            window.paginate = response.data[response.data.length - 1].id;

                            if (!response.last) {
                                document.getElementById('forums').innerHTML += '<div id="loadmore"><center><a href="javascript:void(0);" onclick="window.listForums();">{{ __('app.load_more') }}</a></center></div>';
                            }
                        } else {
                            document.getElementById('forums').innerHTML += '{{ __('app.forums_no_forums_found') }}';
                        }
                    }
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            window.listForums();
        });
    </script>
@endsection
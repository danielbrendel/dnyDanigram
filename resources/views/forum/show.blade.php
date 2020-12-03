{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

@extends('layouts.layout_feed')

@section('title', env('APP_PROJECTNAME') . ' - ' . $forum->name . ' - ' . __('app.forum'))

@section('body')
    <div class="column is-5" id="feed-left">
        <div>
            <h1>{{ $forum->name }}</h1>

            <h2>{{ $forum->description }}</h2>
        </div>

        <div class="field">
            <div class="control">
                <button class="button is-link" onclick="window.vue.bShowCreateThread = true;">{{ __('app.create') }}</button>&nbsp;<button class="button float-right">{{ __('app.go_back') }}</button>
            </div>
        </div>

        <div id="threads"></div>
    </div>

    <div class="column is-3 fixed-frame-parent">
        <div class="fixed-frame is-news-outter">
            <div class="member-form is-default-padding is-news-inner">
                @include('widgets.news')
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

        window.listThreads = function() {
                if (window.paginate === null) {
                    document.getElementById('threads').innerHTML = '<div id="spinner"><center><i class="fas fa-spinner fa-spin"></i></center></div>';
                } else {
                    document.getElementById('threads').innerHTML += '<div id="spinner"><center><i class="fas fa-spinner fa-spin"></i></center></div>';
                }

                if (document.getElementById('loadmore')) {
                    document.getElementById('loadmore').remove();
                }

                window.vue.ajaxRequest('post', '{{ url('/forum/' . $forum->id . '/list') }}', { paginate: window.paginate }, function(response){
                    if (response.code == 200) {
                        if (document.getElementById('spinner')) {
                            document.getElementById('spinner').remove();
                        }

                        if (response.data.length > 0) {
                            response.data.forEach(function(elem, index) {
                                let html = window.renderForumThreadItem(elem);

                                document.getElementById('threads').innerHTML += html;
                            });

                            window.paginate = response.data[response.data.length - 1].id;

                            document.getElementById('threads').innerHTML += '<div id="loadmore"><center><a href="javascript:void(0);" onclick="window.listThreads();">{{ __('app.load_more') }}</a></center></div>';
                        } else {
                            document.getElementById('threads').innerHTML += '{{ __('app.forums_no_threads_found') }}';
                        }
                    }
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            window.listThreads();
        });
    </script>
@endsection
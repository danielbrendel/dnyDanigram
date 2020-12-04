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
                <button class="button is-link" onclick="document.getElementById('forumId').value = '{{ $forum->id }}'; window.vue.bShowCreateThread = true;">{{ __('app.create') }}</button>&nbsp;<button class="button float-right" onclick="location.href = '{{ url('/forum') }}';">{{ __('app.go_back') }}</button>
            </div>
        </div>

        <div>
            @foreach ($stickies as $sticky)
            <div class="forum-thread">
                <div class="forum-thread-infos">
                    <div class="forum-thread-info-id forum-thread-is-sticky">#{{ $sticky->id }}</div>
                    <div class="forum-thread-info-title is-pointer" onclick="location.href = '{{ url('/forum/thread/' . $sticky->id . '/show') }}';">@if ($sticky->sticky) <i class="fas fa-thumbtack"></i> @endif @if ($sticky->locked) <i class="fas fa-lock"></i> @endif {{ $sticky->title }}</div>
                    <div class="forum-thread-info-lastposter">
                    <div class="forum-thread-info-lastposter-avatar"><a href="{{ url('/u/' . $sticky->user->id) }}"><img src="{{ asset('gfx/avatars/' . $sticky->user->avatar) }}" alt="avatar"/></a></div>
                        <div class="forum-thread-info-lastposter-userinfo">    
                            <div class="forum-thread-info-owner-username"><a href="{{ url('/u/' . $sticky->user->id) }}">{{ $sticky->user->username }}</a></div>
                            <div class="forum-thread-info-lastposter-userinfo-date">{{ $sticky->user->diffForHumans }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
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

    <div class="modal" :class="{'is-active': bShowCreateThread}">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head is-stretched">
                <p class="modal-card-title">{{ __('app.create_thread') }}</p>
                <button class="delete" aria-label="close" onclick="vue.bShowCreateThread = false;"></button>
            </header>
            <section class="modal-card-body is-stretched">
                <form id="formCreateThread" method="POST" action="{{ url('/forum/thread/create') }}">
                    @csrf

                    <input type="hidden" id="forumId" name="id">

                    <div class="field">
                        <label class="label">{{ __('app.title') }}</label>
                        <div class="control">
                            <input type="text" name="title" placeholder="{{ __('app.enter_title') }}">
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">{{ __('app.message') }}</label>
                        <div class="control">
                            <textarea name="message" placeholder="{{ __('app.enter_message') }}"></textarea>
                        </div>
                    </div>

                    @if (($user->admin) || ($user->maintainer))
                        <div class="field">
                            <div class="control">
                                <input type="checkbox" data-role="checkbox" data-style="2" data-caption="{{ __('app.set_thread_sticky') }}" value="1">
                            </div>
                        </div>
                    @endif

                    <input type="button" id="createthreadsubmit" onclick="document.getElementById('formCreateThread').submit();" class="is-hidden">
                </form>
            </section>
            <footer class="modal-card-foot is-stretched">
                <button class="button is-success" onclick="document.getElementById('createthreadsubmit').click();">{{ __('app.create') }}</button>
                <button class="button" onclick="vue.bShowCreateThread = false;">{{ __('app.cancel') }}</button>
            </footer>
        </div>
    </div>
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

                            window.paginate = response.data[response.data.length - 1].updated_at;

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
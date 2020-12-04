{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

@extends('layouts.layout_feed')

@section('title', env('APP_PROJECTNAME') . ' - ' . $thread->title . ' - ' . __('app.thread'))

@section('body')
    <div class="column is-5" id="feed-left">
        <div>
            <h1>{{ $thread->title }}</h1>

            <div>
                <div class="is-inline-block is-avatar-icon"><a href="{{ url('/u/' . $thread->owner->id) }}"><img src="{{ asset('gfx/avatars/' . $thread->owner->avatar) }}" alt="avatar"/></a></div>
                <div class="is-inline-block"><a href="{{ url('/u/' . $thread->owner->id) }}">{{ $thread->owner->username }}</a></div>
            </div>
        </div>

        <div>
            <br/><br/>
        </div>

        <div class="field">
            <div class="control">
                <button class="button is-link" onclick="document.getElementById('threadId').value = '{{ $thread->id }}'; window.vue.bShowReplyForumThread = true;">{{ __('app.reply') }}</button>&nbsp;<button class="button float-right" onclick="location.href = '{{ url('/forum/' . $thread->forumId . '/show') }}';">{{ __('app.go_back') }}</button>
            </div>
        </div>

        <div id="postings"></div>
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

    <div class="modal" :class="{'is-active': bShowReplyForumThread}">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head is-stretched">
                <p class="modal-card-title">{{ __('app.reply_thread') }}</p>
                <button class="delete" aria-label="close" onclick="vue.bShowReplyForumThread = false;"></button>
            </header>
            <section class="modal-card-body is-stretched">
                <form id="formReplyForumThread" method="POST" action="{{ url('/forum/thread/reply') }}">
                    @csrf

                    <input type="hidden" id="threadId" name="id">

                    <div class="field">
                        <label class="label">{{ __('app.message') }}</label>
                        <div class="control">
                            <textarea name="message" placeholder="{{ __('app.enter_message') }}"></textarea>
                        </div>
                    </div>

                    <input type="button" id="replythreadsubmit" onclick="document.getElementById('formReplyForumThread').submit();" class="is-hidden">
                </form>
            </section>
            <footer class="modal-card-foot is-stretched">
                <button class="button is-success" onclick="document.getElementById('replythreadsubmit').click();">{{ __('app.reply') }}</button>
                <button class="button" onclick="vue.bShowReplyForumThread = false;">{{ __('app.cancel') }}</button>
            </footer>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        window.paginate = null;

        window.listPostings = function() {
                if (window.paginate === null) {
                    document.getElementById('postings').innerHTML = '<div id="spinner"><center><i class="fas fa-spinner fa-spin"></i></center></div>';
                } else {
                    document.getElementById('postings').innerHTML += '<div id="spinner"><center><i class="fas fa-spinner fa-spin"></i></center></div>';
                }

                if (document.getElementById('loadmore')) {
                    document.getElementById('loadmore').remove();
                }

                window.vue.ajaxRequest('post', '{{ url('/forum/thread/' . $thread->id . '/posts') }}', { paginate: window.paginate }, function(response){
                    if (response.code == 200) {
                        if (document.getElementById('spinner')) {
                            document.getElementById('spinner').remove();
                        }

                        if (response.data.length > 0) {
                            response.data.forEach(function(elem, index) {
                                let html = window.renderForumPostingItem(elem, {{ ($user->admin || $user->maintainer) ? 'true' : 'false' }});

                                document.getElementById('postings').innerHTML += html;
                            });

                            window.paginate = response.data[response.data.length - 1].id;

                            document.getElementById('postings').innerHTML += '<div id="loadmore"><center><a href="javascript:void(0);" onclick="window.listPostings();">{{ __('app.load_more') }}</a></center></div>';
                        } else {
                            document.getElementById('postings').innerHTML += '{{ __('app.forums_no_posts_found') }}';
                        }
                    }
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            window.listPostings();
        });
    </script>
@endsection
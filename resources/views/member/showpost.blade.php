{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

@extends('layouts.layout_member')

@section('body')
    <div class="column is-4 is-sidespacing"></div>

    <div class="column is-4">
        <div class="member-form">
            <div class="show-post-header">
                <div class="show-post-avatar">
                    <img src="{{ asset('gfx/avatars/' . $poster->avatar) }}" class="is-pointer" onclick="location.href='{{ url('/u/' . $poster->id) }}'">
                </div>

                <div class="show-post-userinfo">
                    <div>{{ __('app.posted_by', ['username' => $poster->username]) }}</div>
                    <div title="{{ $post->created_at }}">{{ __('app.posted_at', ['date' => $post->created_at->diffForHumans()]) }}</div>
                </div>
            </div>

            <div class="show-post-image">
                <img class="is-pointer" src="{{ asset('gfx/posts/' . $post->image_thumb) }}" onclick="window.open('{{ asset('gfx/posts/' . $post->image_full) }}')">
            </div>

            <div class="show-post-attributes">
                <div class="is-inline-block"><i class="fas fa-heart"></i>&nbsp;{{ $post->hearts }}</div>
                <div class="is-inline-block is-right" style="float:right;">{{ __('app.comment_count', ['count' => $thread_count]) }}</div>
            </div>

            <div class="show-post-description">
                {{ $post->description }}
            </div>

            <div class="show-post-hashtags">
                @foreach ($post->hashtags as $tag)
                    <a href="{{ url('/t/' . $tag) }}">#{{ $tag }}</a>
                @endforeach
            </div>
        </div>

        <div class="member-form">
            <div class="thread-input">
                <form method="POST" action="{{ url('/p/' . $post->id . '/thread/add') }}">
                    @csrf

                    <div class="thread-input-header">
                        <div class="thread-input-header-avatar is-inline-block">
                            <img src="{{ asset('gfx/avatars/' . $user->avatar) }}" width="24" height="24">
                        </div>

                        <div class="thread-input-header-text is-inline-block">
                            <textarea name="text" placeholder="{{ __('app.type_something') }}"></textarea>
                        </div>
                    </div>

                    <div class="thread-input-submit">
                        <input type="submit" class="button is-success" value="{{ __('app.submit') }}">
                    </div>
                </form>
            </div>

            <div class="thread">
                <a name="thread"></a>

                <div id="thread"></div>
                <div id="loading" style="display: none;"><center><i class="fas fa-spinner fa-spin"></i></center></div>
                <div id="loadmore" style="display: none;"><center><i class="fas fa-arrow-down is-pointer" onclick="fetchThread()"></i></center></div>
            </div>
        </div>
    </div>

    <div class="column is-4 is-sidespacing"></div>
@endsection

@section('javascript')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.paginate = null;

            fetchThread();
        });

        function fetchThread()
        {
            document.getElementById('loading').style.display = 'block';
            document.getElementById('loadmore').style.display = 'none';

            window.vue.ajaxRequest('get', '{{ url('/fetch/thread') }}?post={{ $post->id }}' + ((window.paginate !== null) ? '&paginate=' + window.paginate : ''), {}, function(response){
                if (response.code == 200) {
                    response.data.forEach(function(elem, index) {
                        let insertHtml = renderThread(elem);
                        document.getElementById('thread').innerHTML += insertHtml;

                        window.paginate = response.data[response.data.length - 1].id;

                        document.getElementById('loading').style.display = 'none';
                        document.getElementById('loadmore').style.display = 'block';

                        if (response.last) {
                            document.getElementById('loadmore').innerHTML = '<br/><br/><center><i class="is-color-grey">{{ __('app.no_more_comments')  }}</i></center>';
                        }
                    });
                }
            });
        }
    </script>
@endsection

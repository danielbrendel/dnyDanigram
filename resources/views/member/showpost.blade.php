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
        <div id="singlepost"><center><i class="fas fa-spinner fa-spin"></i></center></div>

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

            fetchSinglePost();
            fetchThread();
        });

        function fetchSinglePost()
        {
            document.getElementById('loading').style.display = 'block';
            document.getElementById('loadmore').style.display = 'none';

            window.vue.ajaxRequest('get', '{{ url('/fetch/post') }}?post={{ $post->id }}', {}, function(response){
                if (response.code == 200) {
                    let insertHtml = renderPost(response.elem);
                    document.getElementById('singlepost').innerHTML = insertHtml;
                }
            });
        }

        function fetchThread()
        {
            document.getElementById('loading').style.display = 'block';
            document.getElementById('loadmore').style.display = 'none';

            window.vue.ajaxRequest('get', '{{ url('/fetch/thread') }}?post={{ $post->id }}' + ((window.paginate !== null) ? '&paginate=' + window.paginate : ''), {}, function(response){
                if (response.code == 200) {
                    response.data.forEach(function(elem, index) {
                        let insertHtml = renderThread(elem, elem.adminOrOwner);
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

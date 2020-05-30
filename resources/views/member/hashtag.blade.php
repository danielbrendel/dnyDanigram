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
    <div class="column is-2 is-sidespacing"></div>

    <div class="column is-4" id="feed-left">
        <div class="feed-nav">
            <span><a id="linkFetchTop" href="javascript:void(0)" onclick="window.vue.setPostFetchType(1); document.getElementById('feed').innerHTML = ''; window.paginate = null; fetchPosts();">{{ __('app.top') }}</a></span> | <span><a id="linkFetchLatest" href="javascript:void(0)" onclick="window.vue.setPostFetchType(2); document.getElementById('feed').innerHTML = ''; window.paginate = null; fetchPosts();">{{ __('app.latest') }}</a></span>
        </div>

        <div id="feed"></div>
        <div id="loading" style="display: none;"><i class="fas fa-spinner fa-spin"></i></div>
    </div>

    <div class="column is-4 fixed-frame-parent">
        <div class="fixed-frame">
            @include('member.support_tag', ['heart_count' => $tagdata->hearts])
            @include('member.fixed_frame')
        </div>
    </div>

    <div class="column is-2 is-sidespacing fa-3x"></div>
@endsection

@section('javascript')
    <script>
        window.hashtag = '{{ $hashtag }}';

        window.onscroll = function(ev) {
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
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
            document.getElementById('loading').style.display = 'block';

            if (window.vue.getPostFetchType() == 1) {
                document.getElementById('linkFetchTop').style.textDecoration = 'underline';
                document.getElementById('linkFetchLatest').style.textDecoration = 'none';
            } else if (window.vue.getPostFetchType() == 2) {
                document.getElementById('linkFetchTop').style.textDecoration = 'none';
                document.getElementById('linkFetchLatest').style.textDecoration = 'underline';
            }

            window.vue.ajaxRequest('GET', '{{ url('/fetch') }}?type=' + window.vue.getPostFetchType() + '&hashtag=' + window.hashtag + ((window.paginate !== null) ? '&paginate=' + window.paginate : ''), {}, function(response){
                if (response.code == 200) {
                    response.data.forEach(function(elem, index) {
                        let insertHtml = renderPost(elem);

                        document.getElementById('feed').innerHTML += insertHtml;

                        if (window.vue.getPostFetchType() == 1) {
                            window.paginate = response.data[response.data.length - 1].hearts;
                        } else if (window.vue.getPostFetchType() == 2) {
                            window.paginate = response.data[response.data.length - 1].id;
                        }

                        document.getElementById('loading').style.display = 'none';
                    });
                }
            });
        }
    </script>
@endsection

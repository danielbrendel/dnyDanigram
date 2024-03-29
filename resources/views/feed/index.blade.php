{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2022 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

@extends('layouts.layout_feed')

@section('title', env('APP_PROJECTNAME'))

@section('body')
    <div class="column is-5" id="feed-left">
        <div class="feed-nav is-default-padding">
            <div class="tabs is-member-form-without-border-and-backgroundcolor is-foreground">
                <ul>
                    <li id="linkFetchTop">
                        <a href="javascript:void(0)" onclick="window.vue.setPostFetchType(1); document.getElementById('feed').innerHTML = ''; window.paginate = null; fetchPosts();" class="is-color-grey">{{ __('app.top') }}</a>
                    </li>
                    <li id="linkFetchLatest">
                        <a href="javascript:void(0)" onclick="window.vue.setPostFetchType(2); document.getElementById('feed').innerHTML = ''; window.paginate = null; fetchPosts();" class="is-color-grey">{{ __('app.latest') }}</a>
                    </li>
                    @auth
                        <li id="linkFetchFavs">
                            <a href="javascript:void(0)" onclick="window.vue.setPostFetchType(3); document.getElementById('feed').innerHTML = ''; window.paginate = null; fetchPosts();" class="is-color-grey">{{ __('app.favorites') }}</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>

        <div id="feed"></div>
        <div id="loading" style="display: none;"><br/><br/><center><i class="fas fa-spinner fa-spin"></i></center></div>
    </div>

    <div class="column is-3 fixed-frame-parent">
        <div class="fixed-frame ">
            <div class="member-form is-default-padding">
				@include('widgets.userbaseinfo', ['user' => \App\User::getUserBaseInfo(auth()->id())])
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
        window.onscroll = function(ev) {
            if ((window.scrollY + window.innerHeight) >= document.body.scrollHeight - 10) {
                fetchPosts();
            }
        };

        window.onresize = function() {
          if (window.innerWidth < 1280) {
              document.getElementById('feed-left').classList.remove('is-5');
              document.getElementById('feed-left').classList.add('is-8');
          } else {
              document.getElementById('feed-left').classList.remove('is-8');
              document.getElementById('feed-left').classList.add('is-5');
          }
        };

        document.addEventListener('DOMContentLoaded', function() {
            window.paginate = null;
            window.mayFetchPosts = true;

            fetchPosts();
        });

        function fetchPosts()
        {
            if (document.getElementById('no-more-posts') !== null) {
                return;
            }

            document.getElementById('loading').style.display = 'block';

            @guest
                if (window.vue.getPostFetchType() == 3) {
                    window.vue.setPostFetchType(2);
                }
            @endguest

            if (window.vue.getPostFetchType() == 1) {
                document.getElementById('linkFetchTop').classList.add('is-active');
                document.getElementById('linkFetchLatest').classList.remove('is-active');
                @auth
                    document.getElementById('linkFetchFavs').classList.remove('is-active');
                @endauth
            } else if (window.vue.getPostFetchType() == 2) {
                document.getElementById('linkFetchTop').classList.remove('is-active');
                document.getElementById('linkFetchLatest').classList.add('is-active');
                @auth
                    document.getElementById('linkFetchFavs').classList.remove('is-active');
                @endauth
            } else if (window.vue.getPostFetchType() == 3) {
                document.getElementById('linkFetchTop').classList.remove('is-active');
                document.getElementById('linkFetchLatest').classList.remove('is-active');
                @auth
                    document.getElementById('linkFetchFavs').classList.add('is-active');
                @endauth
            }

            if (window.mayFetchPosts) {
                window.mayFetchPosts = false;
                window.vue.ajaxRequest('GET', '{{ url('/fetch/posts') }}?type=' + window.vue.getPostFetchType() + ((window.paginate !== null) ? '&paginate=' + window.paginate : ''), {}, function(response){
                    if (response.code == 200) {
                        if (!response.last) {
                            response.data.forEach(function (elem, index) {
                                let adminOrOwner = false;

                                @auth
                                    adminOrOwner = ({{ $user->admin }}) || ({{ $user->id }} === elem.userId);
                                @endauth

                                let nsfwFlag = 0;

                                @auth
                                    nsfwFlag = {{ (int)$user->nsfw }};
                                @endauth

                                let isGuest = @auth {{ 'false' }} @elseguest {{ 'true' }} @endauth ;

                                let insertHtml = renderPost(elem, adminOrOwner, nsfwFlag, {{ env('APP_ENABLENSFWFILTER') }}, isGuest);

                                document.getElementById('feed').innerHTML += insertHtml;

                                //window.renderPosterImage();

                                if (window.vue.getPostFetchType() == 1) {
                                    if (response.data[response.data.length - 1]._type === 'ad') {
                                        window.paginate = response.data[response.data.length - 2].hearts;
                                    } else {
                                        window.paginate = response.data[response.data.length - 1].hearts;
                                    }
                                } else if ((window.vue.getPostFetchType() == 2) || (window.vue.getPostFetchType() == 3)) {
                                    if (response.data[response.data.length - 1]._type === 'ad') {
                                        window.paginate = response.data[response.data.length - 2].id;
                                    } else {
                                        window.paginate = response.data[response.data.length - 1].id;
                                    }
                                }

                                document.getElementById('loading').style.display = 'none';
                            });

                            let tagElems = [];
                            let adsNodes = document.getElementsByClassName('is-advertisement');
                            if (adsNodes.length > 0) {
                                let childNodes = adsNodes[adsNodes.length - 1].childNodes;
                                for (let i = 0; i < childNodes.length; i++) {
                                    if (typeof childNodes[i].tagName !== 'undefined') {
                                        let childTag = document.createElement(childNodes[i].tagName);
                                        let tagCode = document.createTextNode(childNodes[i].innerHTML);
                                        childTag.appendChild(tagCode);
                                        tagElems.push(childTag);
                                    }
                                }

                                adsNodes[adsNodes.length - 1].innerHTML = '';

                                for (let i = 0; i < tagElems.length; i++) {
                                    adsNodes[adsNodes.length - 1].appendChild(tagElems[i]);
                                }
                            }
                        } else {
                            if (document.getElementById('no-more-posts') == null) {
                                document.getElementById('feed').innerHTML += '<div id="no-more-posts"><br/><br/><center><i>{{ __('app.no_more_posts') }}</i></center><br/></div>';
                            }

                            document.getElementById('loading').style.display = 'none';
                        }
                    }

                    window.mayFetchPosts = true;
                });
            }
        }
    </script>
@endsection

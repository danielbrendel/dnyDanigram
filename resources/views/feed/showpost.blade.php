{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2021 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

@extends('layouts.layout_feed')

@section('title', env('APP_PROJECTNAME') . ' - ' . __('app.view_post'))

@section('body')
    <div class="column is-5">
        <div id="singlepost" class="is-fix-single-post-position"><center><i class="fas fa-spinner fa-spin"></i></center></div>

        <div class="member-form is-default-padding">
            <div class="thread-input">
                <form id="frmSubmitComment" method="POST" action="{{ url('/p/' . $post->id . '/thread/add') }}">
                    @csrf

                    <div class="thread-input-header">
                        <div class="thread-input-header-avatar is-inline-block">
							@auth
								<img src="{{ asset('gfx/avatars/' . $user->avatar) }}" width="24" height="24">
							@elseguest
								<img src="{{ asset('gfx/avatars/default.png') }}" width="24" height="24">
							@endauth
                        </div>

                        <div class="thread-input-header-text is-inline-block">
                            <div id="input-text"></div>
                            <textarea name="text" id="post-text" class="is-hidden" placeholder="{{ __('app.type_something') }}"></textarea>
                        </div>
                    </div>

                    <div class="thread-input-submit">
						<button class="button" type="button" onclick="@auth {{ 'document.getElementById(\'frmSubmitComment\').submit();' }} @elseguest {{ 'window.vue.bShowLogin = true;' }} @endauth">{{ __('app.submit') }}</button>
                    </div>
                </form>
            </div>

            <div class="thread">
                <a name="thread"></a>

                @if ($highlight_comment !== null)
                    <div id="thread-{{ $highlight_comment->id }}" class="is-highlighted-comment">
                        <a name="{{ $highlight_comment->id }}"></a>

                        <div class="thread-header">
                            <div class="thread-header-avatar is-inline-block">
                                <img width="24" height="24" src="{{ url('/gfx/avatars/' . $highlight_comment->user->avatar) }}" class="is-pointer" onclick="location.href = '{{ url('/u/' . $highlight_comment->user->username) }}';" title="">
                            </div>

                            <div class="thread-header-info is-inline-block">
                                <div><a href="{{ url('/u/' . $highlight_comment->user->username) }}" class="is-color-grey">{{ $highlight_comment->user->username }}</a></div>
                                <div title="{{ $highlight_comment->created_at }}">{{ $highlight_comment->created_at->diffForHumans() }}</div>
                            </div>

                            <div class="thread-header-options is-inline-block">
                                <div class="dropdown is-right" id="thread-options-{{ $highlight_comment->id }}">
                                    <div class="dropdown-trigger">
                                        <i class="fas fa-ellipsis-v is-pointer" onclick="window.vue.togglePostOptions(document.getElementById('thread-options-{{ $highlight_comment->id }}'));"></i>
                                    </div>
                                    <div class="dropdown-menu" role="menu">
                                        <div class="dropdown-content">
                                            @if ($highlight_comment->adminOrOwner)
                                                <a onclick="showEditComment({{ $highlight_comment->id }}); window.vue.toggleCommentOptions(document.getElementById('thread-options-{{ $highlight_comment->id }}'));" href="javascript:void(0)" class="dropdown-item">
                                                    <i class="far fa-edit"></i>&nbsp;Edit
                                                </a>
                                                <a onclick="lockComment(` + elem.id + `); window.vue.toggleCommentOptions(document.getElementById('thread-options-` + elem.id + `'));" class="dropdown-item">
                                                    <i class="fas fa-times"></i>&nbsp;Lock
                                                </a>
                                                <hr class="dropdown-divider">
                                            @endif

                                            <a href="javascript:void(0)" onclick="reportComment({{ $highlight_comment->id }}); window.vue.togglePostOptions(document.getElementById('thread-options-{{ $highlight_comment->id }}'));" class="dropdown-item">
                                                Report
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="thread-text" id="thread-text-{{ $highlight_comment->id }}">
                            {{ $highlight_comment->text }}
                        </div>

                        <div class="thread-footer">
                            <div class="thread-footer-hearts"><i id="heart-ent_comment-{{ $highlight_comment->id }}" class="{{ (($highlight_comment->userHearted) ? 'fas fa-heart is-hearted': 'far fa-heart') }} is-pointer" onclick="window.vue.toggleHeart({{ $highlight_comment->id }}, 'ENT_COMMENT')"></i>&nbsp;<span id="count-ent_comment-{{ $highlight_comment->id }}">{{ $highlight_comment->hearts }}</span></div>
                        </div>
                    </div>
                @endif

                <div id="thread"></div>
                <div id="loading" style="display: none;"><center><i class="fas fa-spinner fa-spin"></i></center></div>
                <div id="loadmore" style="display: none;"><center><i class="fas fa-arrow-down is-pointer" onclick="fetchThread()"></i></center></div>
            </div>
        </div>
    </div>

    <div class="column is-3 fixed-frame-parent">
        <div class="fixed-frame">
            <div class="member-form is-default-padding">
                @include('widgets.userbaseinfo', ['user' => \App\User::getUserBaseInfo($post->userId)])
            </div>

            <div class="member-form is-default-padding">
                @include('widgets.newusers', ['users' => \App\User::getNewestUsers()])
            </div>

            <div class="member-form is-default-padding is-margin-bottom-last-fixed-frame is-member-form-without-border-and-backgroundcolor">
                @include('widgets.company')
            </div>
        </div>
    </div>

    <div class="column is-5 is-sidespacing"></div>
@endsection

@section('javascript')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.paginate = null;

			var quillEditor = new Quill('#input-text', {
				theme: 'snow',
				placeholder: '{{ __('app.type_something') }}',
			});

			quillEditor.on('editor-change', function(eventName, ...args) {
				document.getElementById('post-text').value = quillEditor.root.innerHTML;
			});

            fetchSinglePost();
            fetchThread();
        });

        function fetchSinglePost()
        {
            document.getElementById('loading').style.display = 'block';
            document.getElementById('loadmore').style.display = 'none';

            window.vue.ajaxRequest('get', '{{ url('/fetch/post') }}?post={{ $post->id }}', {}, function(response){
                if (response.code == 200) {
                    adminOrOwner = false;

                    @auth
                        adminOrOwner = ({{ $user->admin }}) || ({{ $user->id }} === elem.userId);
                    @endauth

                    let nsfwFlag = 0;

                    @auth
                        nsfwFlag = {{ (int)$user->nsfw }};
                    @endauth

                    let insertHtml = renderPost(response.elem, adminOrOwner, nsfwFlag, {{ env('APP_ENABLENSFWFILTER') }});
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
                    if (response.data.length > 0) {
                        response.data.forEach(function (elem, index) {
                            let insertHtml = renderThread(elem, elem.adminOrOwner);
                            document.getElementById('thread').innerHTML += insertHtml;
                        });

                        window.paginate = response.data[response.data.length - 1].id;

                        document.getElementById('loading').style.display = 'none';
                        document.getElementById('loadmore').style.display = 'block';

                        if (response.last) {
                            document.getElementById('loading').innerHTML = '<br/><br/><center><i class="is-color-grey">{{ __('app.no_more_comments')  }}</i></center>';
                        }
                    } else {
                        document.getElementById('loading').style.display = 'none';

                        if (window.paginate === null) {
                            document.getElementById('loading').innerHTML = '<br/><br/><center><i class="is-color-grey">{{ __('app.no_comments_yet')  }}</i></center>';
                        } else {
                            if (document.getElementById('no-more-comments') == null) {
                                document.getElementById('thread').innerHTML += '<div id="no-more-comments"><br/><br/><center><i>{{ __('app.no_more_comments') }}</i></center><br/></div>';
                            }
                        }
                    }
                }
            });
        }
    </script>
@endsection

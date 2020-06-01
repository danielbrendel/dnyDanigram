{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

<div class="userinfo-header">
    <div class="userinfo-image is-inline-block">
        <img src="{{ asset('gfx/avatars/' . $user->avatar) }}" width="24" height="24">
    </div>

    <div class="userinfo-name is-inline-block is-pointer" onclick="location.href='{{ url('/u/' . $user->id) }}';">
        {{ $user->username }}
        &nbsp;&nbsp;&nbsp;
        @if ($user->admin)
            <div class="badge-admin is-inline-block">
                <p>{{ __('app.admin') }}</p>
            </div>
        @endif
    </div>
</div>

<div class="userinfo-bio">
    {{ $user->bio }}
</div>

<div class="userinfo-stats">
    <i class="far fa-calendar-alt" title="{{ $user->created_at }}"></i>&nbsp;{{ __('app.registered_since', ['date' => $user->created_at->diffForHumans()]) }}<br/>
    <i class="far fa-file-image"></i>&nbsp;{{ __('app.stats_posts', ['count' => $user->stats->posts]) }}<br/>
    <i class="far fa-comment"></i>&nbsp;{{ __('app.stats_posts', ['count' => $user->stats->comments]) }}<br/>
</div>

@if ($user->id === auth()->id())
    <div class="userinfo-edit">
        <a href="javascript:void(0)" onclick="window.vue.bShowEditProfile = true;">{{ __('app.edit_profile') }}</a>
    </div>
@else
    <div class="userinfo-bookmark" id="bookmark-ent_user">
        @if ($bookmarked)
            <a href="javascript:void(0)" onclick="removeBookmark({{ $user->id }}, 'ENT_USER')">{{ __('app.bookmark_remove') }}</a>
        @else
            <a href="javascript:void(0)" onclick="addBookmark({{ $user->id }}, 'ENT_USER')">{{ __('app.bookmark_add') }}</a>
        @endif
    </div>

    @if (!$admin)
    <div class="userinfo-report float-right">
        <a href="javascript:void(0)" onclick="reportProfile({{ $user->id }})">{{ __('app.report_profile') }}</a>
    </div>
    @endif
@endif

@if ($admin)
    <div class="userinfo-lock float-right">
        <a href="javascript:void(0)" onclick="lockUser({{ $user->id }})">{{ __('app.lock_profile') }}</a>
    </div>
@endif

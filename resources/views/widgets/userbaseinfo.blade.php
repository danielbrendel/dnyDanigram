{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2021 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

<div class="ubi-frame">
    <div class="ubi-center-item">
        <div class="ubi-avatar">
            <a href="@if (isset($user->id)) {{ url('/u/' . $user->id) }} @else{{ 'javascript:void(0);' }} @endif" @if (!isset($user->id)) {!! 'onclick="window.vue.bShowLogin = true;"' !!} @endif><img src="{{ asset('gfx/avatars/' . $user->avatar) }}" alt="avatar"></a>
        </div>
    </div>

    <div class="ubi-center-item">
        <div class="ubi-username">
            <a href="@if (isset($user->id)) {{ url('/u/' . $user->id) }} @else{{ 'javascript:void(0);' }} @endif" @if (!isset($user->id)) {!! 'onclick="window.vue.bShowLogin = true;"' !!} @endif>{{ $user->username }}</a>
        </div>
    </div>

    <div class="ubi-stats">
        <div class="ubi-center-item">
            <div class="ubi-stats-item">
                <div class="is-bold">{{ __('app.posts') }}</div>
                <div>{{ $user->stats->posts }}</div>
            </div>
            <div class="ubi-stats-item ubi-stats-item-middle">
                <div class="is-bold">{{ __('app.subscribers') }}</div>
                <div>{{ $user->stats->subscribers }}</div>
            </div>
            <div class="ubi-stats-item">
                <div class="is-bold">{{ __('app.subscribed') }}</div>
                <div>{{ $user->stats->subscribed }}</div>
            </div>
        </div>
    </div>
</div>

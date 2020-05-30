{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

<div class="member-form">
    <h2 class="is-default-headline-color">{{ __('app.popular_tags') }}</h2>

    <div>
        @foreach ($taglist as $tag)
            <div class="taglist-item is-block">
                <div class="taglist-item-left is-inline-block"><a href="{{ url('/t/' . $tag->tag) }}">#{{ $tag->tag }}</a></div>
                <div class="taglist-item-right is-inline-block"><i class="far fa-heart"></i>&nbsp;{{ $tag->hearts }}</div>
            </div>
        @endforeach
    </div>
</div>

<div class="member-form">
    <div class="userinfo-header">
        <div class="userinfo-image is-inline-block">
            <img src="{{ asset('gfx/avatars/' . $user->avatar) }}" width="24" height="24">
        </div>

        <div class="userinfo-name is-inline-block is-pointer" onclick="location.href='{{ url('/p/' . $user->id) }}';">
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
        <i class="far fa-file-image"></i>&nbsp;{{ $user->stats->posts }}<br/>
        <i class="far fa-comment"></i>&nbsp;{{ $user->stats->comments }}<br/>
    </div>
</div>

<div class="member-form is-about-nav">
    &copy; {{ date('Y') }} {{ env('APP_NAME') }} | <a href="{{ url('/about') }}" target="_blank">{{ __('app.about') }}</a>&nbsp;&nbsp;<a href="{{ url('/faq') }}" target="_blank">{{ __('app.faq') }}</a>&nbsp;&nbsp;<a href="{{ url('/tos') }}" target="_blank">{{ __('app.tos') }}</a>&nbsp;&nbsp;<a href="{{ url('/imprint') }}" target="_blank">{{ __('app.imprint') }}</a>
</div>

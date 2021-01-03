{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2021 by Daniel Brendel

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
    </div>
</div>

@if (strlen($user->bio) > 0)
    <div class="userinfo-bio">
        {{ $user->bio }}
    </div>
@endif

<div class="userinfo-badges">

    @if ($user->maintainer)
        <div class="is-inline-block">
            <div class="member-badge member-badge-maintainer"><p>{{ __('app.maintainer') }}</p></div>
        </div>
    @endif



    @if ($user->admin)
        <div class="is-inline-block">
            <div class="member-badge member-badge-admin"><p>{{ __('app.admin') }}</p></div>
        </div>
    @endif

    @if ($user->pro)
        <div class="is-inline-block">
            <div class="member-badge member-badge-pro"><p>{{ __('app.pro') }}</p></div>
        </div>
    @endif

</div>

<div class="userinfo-stats">
    <i class="far fa-calendar-alt" title="{{ $user->created_at }}"></i>&nbsp;{{ __('app.registered_since', ['date' => $user->created_at->diffForHumans()]) }}<br/>
    <i class="far fa-file-image"></i>&nbsp;{{ __('app.stats_posts') }}: {{ $user->stats->posts }}<br/>
    <i class="far fa-comment"></i>&nbsp;{{ __('app.stats_comments', ['count' => $user->stats->comments]) }}<br/>
    <i class="@if ($user->gender == 1) {{ 'fas fa-mars' }} @elseif ($user->gender == 2) {{ 'fas fa-venus' }} @elseif ($user->gender == 3) {{ 'fas fa-transgender-alt' }} @else {{ 'fas fa-genderless' }} @endif"></i>&nbsp;{{ $user->genderStr }}<br/>
    <i class="fas fa-star-of-life"></i>&nbsp;{{ __('app.age_info', ['value' => $user->age]) }}<br/>
    <i class="fas fa-map-marker-alt"></i>&nbsp;{{ ucfirst($user->location) }}<br/>
</div>

<div class="userinfo-profile-data">
    @foreach ($profile as $key => $item)
        @if (strlen($item['content']) > 0)
            <div class="userinfo-profile-data-item">
                {{ $key }}: <i>{{ $item['content'] }}</i>
            </div>
        @endif
    @endforeach
</div>

@auth
    @if (auth()->id() !== $user->id)
        <div class="userinfo-message">
            <a href="{{ url('/messages/create?u=' . $user->username) }}">{{ __('app.send_message') }}</a>
        </div>
    @endif

    @if ($user->id === auth()->id())
        <div class="userinfo-edit">
            <a href="javascript:void(0)" onclick="window.vue.bShowEditProfile = true;">{{ __('app.edit_profile') }}</a>
        </div>
    @else
        <div class="userinfo-favorite favorite-ent_user">
            @if ($favorited)
                <a href="javascript:void(0)" onclick="removeFavorite({{ $user->id }}, 'ENT_USER', '{{ $user->username }}')">{{ __('app.favorite_remove') }}</a>
            @else
                <a href="javascript:void(0)" onclick="addFavorite({{ $user->id }}, 'ENT_USER', '{{ $user->username }}')">{{ __('app.favorite_add') }}</a>
            @endif
        </div>

        @if (!$admin)
        <div class="userinfo-report float-right">
            <a href="javascript:void(0)" onclick="reportProfile({{ $user->id }})">{{ __('app.report_profile') }}</a>
        </div>
        @endif

        <div>
            @if ($user->ignored)
                <a href="javascript:void(0)" onclick="location.href = '{{ url('/u/' . $user->id . '/ignore/remove') }}';">{{ __('app.remove_from_ignore') }}</a>
            @else
                <a href="javascript:void(0)" onclick="location.href = '{{ url('/u/' . $user->id . '/ignore/add') }}';">{{ __('app.add_to_ignore') }}</a>
            @endif
        </div>
    @endif

    @if (($admin) && ($user->id !== auth()->id()))
        <div class="userinfo-lock float-right">
            <a href="javascript:void(0)" onclick="lockUser({{ $user->id }})">{{ __('app.lock_profile') }}</a>
        </div>
    @endif
@endauth

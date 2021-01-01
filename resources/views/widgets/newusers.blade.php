{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2021 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

<div class="nu-header">
    {{ __('app.newest_users') }}
</div>

<div class="nu-frame">
    @foreach ($users as $user)
        <div class="nu-item">
            <div class="nu-item-avatar">
                <a href="{{ url('/u/' . $user->id) }}"><img src="{{ asset('gfx/avatars/' . $user->avatar) }}" alt="avatar"></a>
            </div>

            <div class="nu-item-centered">
                <div class="nu-item-username">
                    {{ $user->username }}
                </div>
            </div>
        </div>
    @endforeach
</div>
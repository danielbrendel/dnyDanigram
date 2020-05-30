{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

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
    @foreach ($threads as $thread)
        <a name="{{ $thread->id }}"></a>

        <div class="thread-header">
            <div class="thread-header-avatar is-inline-block">
                <img width="24" height="24" src="{{ asset('gfx/avatars/' . $thread->user->avatar) }}" class="is-pointer" onclick="location.href = '';" title="">
            </div>

            <div class="thread-header-info is-inline-block">
                <div>{{ $thread->user->username }}</div>
                <div title="{{ $thread->created_at }}">{{ $thread->created_at->diffForHumans() }}</div>
            </div>
        </div>

        <div class="thread-text">
            {{ $thread->text }}
        </div>

        <div class="thread-footer">
            <div class="thread-footer-hearts"><i class="far fa-heart"></i>&nbsp;{{ $thread->hearts }}</div>
            <div class="thread-footer-options">
                @if ($thread->user->id = auth()->id())
                <a href="">{{ __('app.edit') }}</a> | <a href="">{{ __('app.delete') }}</a> |
                @endif
                <a href="">{{ __('app.report') }}</a>
            </div>
        </div>
    @endforeach
</div>

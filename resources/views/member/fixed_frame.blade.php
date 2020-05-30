{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

<div class="member-form">
    <h2>{{ __('app.popular_tags') }}</h2>

    <div>
        @foreach ($taglist as $tag)
            <a href="{{ url('/t/' . $tag->tag) }}">#{{ $tag->tag }}</a>&nbsp;
        @endforeach
    </div>
</div>

<div class="member-form">
    &copy; {{ date('Y') }} {{ env('APP_NAME') }} | <a href="{{ url('/about') }}" target="_blank">{{ __('app.about') }}</a>&nbsp;&nbsp;<a href="{{ url('/faq') }}" target="_blank">{{ __('app.faq') }}</a>&nbsp;&nbsp;<a href="{{ url('/tos') }}" target="_blank">{{ __('app.tos') }}</a>&nbsp;&nbsp;<a href="{{ url('/imprint') }}" target="_blank">{{ __('app.imprint') }}</a>
</div>

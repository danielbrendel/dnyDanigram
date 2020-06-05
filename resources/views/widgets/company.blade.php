{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

<div class="is-about-nav">
    <center>&copy; {{ date('Y') }} {{ env('APP_PROJECTNAME') }} </center> <center> @if (env('TWITTER_NEWS', null) !== null) <a href="{{ url('/news') }}" target="_blank">{{ __('app.news') }}</a>&nbsp;&nbsp;@endif<a href="{{ url('/about') }}" target="_blank">{{ __('app.about') }}</a>&nbsp;&nbsp;<a href="{{ url('/faq') }}" target="_blank">{{ __('app.faq') }}</a>&nbsp;&nbsp;<a href="{{ url('/tos') }}" target="_blank">{{ __('app.tos') }}</a>&nbsp;&nbsp;<a href="{{ url('/imprint') }}" target="_blank">{{ __('app.imprint') }}</a>&nbsp;&nbsp;@if (env('HELPREALM_WORKSPACE') !== null) <a href="{{ url('/contact') }}" target="_blank">{{ __('app.contact') }}</a>@endif</center>
</div>

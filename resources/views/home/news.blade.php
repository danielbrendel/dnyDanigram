{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

@extends('layouts.layout_home')

@section('title', env('APP_TITLE') . ' - ' . __('app.imprint'))

@section('body')
    <div class="column is-2 is-sidespacing"></div>

    <div class="column is-8">
        <div class="has-text-centered info-headline"><h1>{{ __('app.news') }}</h1></div>

        <div>
            <a class="twitter-timeline" href="https://twitter.com/{{ env('TWITTER_NEWS') }}?ref_src=twsrc%5Etfw">Tweets by {{ env('TWITTER_NEWS') }}</a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
        </div>
    </div>

    <div class="column is-2 is-sidespacing"></div>
@endsection

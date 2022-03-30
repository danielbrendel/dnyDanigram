{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2022 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

@extends('layouts.layout_home')

@section('title', env('APP_TITLE') . ' - ' . __('app.about'))

@section('body')
    <div class="column is-2 is-sidespacing"></div>

    <div class="column is-8 is-default-padding">
        <div class="has-text-centered info-headline"><h1>{{ __('app.faq') }}</h1></div>

        <div>
            <div data-role="accordion" data-one-frame="true" data-show-active="true">
                @foreach ($faqs as $faq)
                    <div class="frame">
                        <div class="heading">{{ $faq->question }}</div>
                        <div class="content">
                            <div class="p-2">{{ $faq->answer }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @if ((!isset($_GET['ngb'])) || ($_GET['ngb'] == 0))
        <div class="home-go-back">
            <a href="javascript:window.history.back();">{{ __('app.go_back') }}</a>
        </div>
        @endif
    </div>

    <div class="column is-2 is-sidespacing"></div>
@endsection

{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

@extends('layouts.layout_home')

@section('title', env('APP_TITLE') . ' - ' . __('app.about'))

@section('body')
    <div class="column is-2 is-sidespacing"></div>

    <div class="column is-8">
        <div class="has-text-centered info-headline"><h1>{{ __('app.contact') }}</h1></div>

        <div class="member-form is-default-padding">
            <form method="POST" action="{{ url('/contact') }}">
                @csrf

                <div class="field">
                    <label class="label">{{ __('app.contact_name') }}</label>
                    <div class="control">
                        <input type="text" name="name">
                    </div>
                </div>

                <div class="field">
                    <label class="label">{{ __('app.contact_email') }}</label>
                    <div class="control">
                        <input type="email" name="email">
                    </div>
                </div>

                <div class="field">
                    <label class="label">{{ __('app.contact_subject') }}</label>
                    <div class="control">
                        <input type="text" name="subject">
                    </div>
                </div>

                <div class="field">
                    <label class="label">{{ __('app.contact_body') }}</label>
                    <div class="control">
                        <textarea name="body"></textarea>
                    </div>
                </div>

                <div class="field">
                    <div class="control">
                        <input type="submit" value="{{ __('app.submit') }}">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="column is-2 is-sidespacing"></div>
@endsection

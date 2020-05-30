{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

@extends('layouts.layout_member')

@section('body')
    <div class="column is-2 is-sidespacing"></div>

    <div class="column is-8">
        <div class="member-form">
            <div>
                <h1>{{ __('app.upload_title') }}</h1>
            </div>

            <div>
                <form method="POST" action="{{ url('/upload') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="field">
                        <input type="file" data-role="file" data-mode="drop" name="image">
                    </div>

                    <div class="field">
                        <label class="label">{{ __('app.description') }}</label>
                        <div class="control">
                            <textarea class="textarea" name="description" placeholder="{{ __('app.upload_description') }}"></textarea>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">{{ __('app.hashtags') }}</label>
                        <div class="control">
                            <textarea class="textarea" name="hashtags" placeholder="{{ __('app.upload_hashtags') }}"></textarea>
                        </div>
                    </div>

                    <div>
                        <hr/>
                    </div>

                    <div>
                        <input type="submit" value="{{ __('app.upload_button') }}"/>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="column is-2 is-sidespacing"></div>
@endsection

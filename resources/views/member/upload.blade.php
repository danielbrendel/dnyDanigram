{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

@extends('layouts.layout_feed')

@section('title', env('APP_PROJECTNAME') . ' - ' . __('app.post_title'))

@section('body')
    <div class="column is-8">
        <div class="member-form is-default-padding member-form-fixed-top">
            <div class="is-margin-top-15">
                <h1>{{ __('app.post_title') }}</h1>
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
                            <textarea class="textarea" name="description" placeholder="{{ __('app.post_description') }}"></textarea>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">{{ __('app.hashtags') }}</label>
                        <div class="control">
                            <textarea class="textarea" name="hashtags" placeholder="{{ __('app.post_hashtags') }}"></textarea>
                        </div>
                    </div>

                    @if (env('APP_ENABLENSFWFILTER'))
                        <div class="field">
                            <div class="control">
                                <input type="checkbox" name="nsfw" data-role="checkbox" data-style="2" data-caption="{{ __('app.nsfw') }}" value="1">
                            </div>
                        </div>
                    @endif

                    <div class="field">
                        <div id="toggle-attribution">
                            <a href="javascript:void(0);" onclick="document.getElementById('attribution-settings').classList.remove('is-hidden');document.getElementById('toggle-attribution').classList.add('is-hidden');">{{ __('app.make_attribution') }}</a>
                        </div>

                        <div id="attribution-settings" class="is-hidden">
                            <label class="label">{{ __('app.instagram_name') }}</label>
                            <div class="field">
                                <input type="text" name="ig_name">
                            </div>

                            <label class="label">{{ __('app.twitter_name') }}</label>
                            <div class="field">
                                <input type="text" name="twitter_name">
                            </div>

                            <label class="label">{{ __('app.homepage_url') }}</label>
                            <div class="field">
                                <input type="text" name="homepage_url" placeholder="http://">
                            </div>
                        </div>
                    </div>

                    <div>
                        <hr/>
                    </div>

                    <div>
                        <input type="submit" onclick="document.getElementById('posting-spinner').classList.remove('is-hidden')" value="{{ __('app.post_button') }}"/>&nbsp;&nbsp;<i id="posting-spinner" class="fas fa-spinner fa-spin is-hidden"></i>
                    </div>
                </form>
            </div>
        </div>
        <br/><br/>
    </div>

    <div class="column is-2 is-sidespacing"></div>
@endsection


{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

@extends('layouts.layout_feed')

@section('title', env('APP_PROJECTNAME') . ' - ' . __('app.geosearch'))

@section('body')
    <div class="column is-5" id="feed-left">
        <div class="is-default-padding-mobile is-mobile-fixed-top">
            <h1>{{ __('app.geosearch_title') }}</h1>

            <h2>{{ __('app.geosearch_subtitle') }}</h2>
        </div>

        <div class="geo-slider is-default-padding-mobile">
            <input id="geo-slider" data-on-change="window.maxrange = arguments[0];" data-role="slider" data-return-type="value" data-hint="true" data-hint-position="top" data-min="5" data-max="{{ env('APP_GEOMAX', 150) }}">
        </div>

        <div class="field is-default-padding-mobile">
            <div class="control">
                <button class="button" onclick="document.getElementById('userlist').innerHTML = ''; window.queryMemberList();">{{ __('app.geosearch') }}</button>
            </div>
        </div>

        <div class="is-default-padding-mobile" id="userlist"></div>

        @if ($user->geo_exclude)
            <div>{{ __('app.geo_exclude_hint') }}</div>
        @endif
    </div>

    <div class="column is-3 fixed-frame-parent">
        <div class="fixed-frame is-news-outter">
            <div class="member-form is-default-padding is-news-inner">
                @include('widgets.news')
            </div>

            <div class="member-form is-default-padding is-margin-bottom-last-fixed-frame is-member-form-without-border-and-backgroundcolor">
                @include('widgets.company')
            </div>
        </div>
    </div>

    <div class="column is-5 is-sidespacing fa-3x"></div>
@endsection

@section('javascript')
    <script>
        window.maxrange = {{ env('APP_GEOMAX', 150) }};
        window.paginate = null;

        document.getElementById('geo-slider').setAttribute('data-value', window.maxrange);

        window.queryMemberList = function() {
            @if (!$user->geo_exclude)
                if (window.paginate === null) {
                    document.getElementById('userlist').innerHTML = '<div id="spinner"><center><i class="fas fa-spinner fa-spin"></i></center></div>';
                } else {
                    document.getElementById('userlist').innerHTML += '<div id="spinner"><center><i class="fas fa-spinner fa-spin"></i></center></div>';
                }

                if (document.getElementById('loadmore')) {
                    document.getElementById('loadmore').remove();
                }

                window.vue.ajaxRequest('post', '{{ url('/geosearch') }}', { distance: window.maxrange, paginate: window.paginate }, function(response){
                    if (response.code == 200) {
                        if (document.getElementById('spinner')) {
                            document.getElementById('spinner').remove();
                        }

                        if (response.data.length > 0) {
                            response.data.forEach(function(elem, index) {
                                let html = window.renderUserItem(elem);

                                document.getElementById('userlist').innerHTML += html;
                            });

                            window.paginate = response.data[response.data.length - 1].id;

                            document.getElementById('userlist').innerHTML += '<div id="loadmore"><center><a href="javascript:void(0);" onclick="window.queryMemberList();">{{ __('app.load_more') }}</a></center></div>';
                        } else {
                            document.getElementById('userlist').innerHTML += '{{ __('app.geosearch_no_users_found') }}';
                        }
                    }
                });
            @endif
        }

        document.addEventListener('DOMContentLoaded', function() {
            //window.queryMemberList();
        });
    </script>
@endsection
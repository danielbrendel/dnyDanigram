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
        <div>
            <h1>{{ __('app.geosearch_title') }}</h1>

            <h2>{{ __('app.geosearch_subtitle') }}</h2>
        </div>

        <div class="geo-slider">
            <input id="geo-slider" data-on-change="window.maxrange = arguments[0]; document.getElementById('userlist').innerHTML = '<center><i class=\'fas fa-spinner fa-spin\'></i></center>'; window.queryMemberList();" data-role="slider" data-return-type="value" data-hint="true" data-hint-position="top" data-min="5" data-max="{{ env('APP_GEOMAX', 150) }}">
        </div>

        <div id="userlist"><center><i class="fas fa-spinner fa-spin"></i></center></div>
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

        document.getElementById('geo-slider').setAttribute('data-value', window.maxrange);

        window.queryMemberList = function() {
            window.vue.ajaxRequest('post', '{{ url('/geosearch') }}', { distance: window.maxrange}, function(response){
                if (response.code == 200) {
                    document.getElementById('userlist').innerHTML = '';

                    response.data.forEach(function(elem, index) {
                        let html = window.renderUserItem(elem);

                        document.getElementById('userlist').innerHTML += html;
                    });  
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            //window.queryMemberList();
        });
    </script>
@endsection
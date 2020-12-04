{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

@extends('layouts.layout_feed')

@section('title', env('APP_PROJECTNAME') . ' - ' . __('app.profile_search'))

@section('body')
    <div class="column is-5" id="feed-left">
        <div class="is-default-padding-mobile is-mobile-fixed-top">
            <h1>{{ __('app.profilesearch_title') }}</h1>

            <h2>{{ __('app.profilesearch_subtitle') }}</h2>
        </div>

        <div class="is-default-padding-mobile">
            <div class="field">
                <label class="label">{{ __('app.username') }}</label>
                <div class="control">
                    <input type="text" id="search-username">
                </div>
            </div>

            <div class="field">
                <label class="label">{{ __('app.bio') }}</label>
                <div class="control">
                    <textarea id="search-bio"></textarea>
                </div>
            </div>

            <div class="field">
                <label class="label">{{ __('app.gender') }}</label>
                <div class="control">
                    <select id="search-gender">
                        <option value="0">{{ __('app.search_all') }}</option>
                        <option value="1">{{ __('app.gender_male') }}</option>
                        <option value="2">{{ __('app.gender_female') }}</option>
                        <option value="3">{{ __('app.gender_diverse') }}</option>
                    </select>
                </div>
            </div>

            <div class="field">
                <label class="label">{{ __('app.age_from') }}</label>
                <div class="control">
                    <input type="number" id="search-age-from">
                </div>
            </div>

            <div class="field">
                <label class="label">{{ __('app.age_till') }}</label>
                <div class="control">
                    <input type="number" id="search-age-till">
                </div>
            </div>

            <div class="field">
                <label class="label">{{ __('app.location') }}</label>
                <div class="control">
                    <input type="text" id="search-location">
                </div>
            </div>

            <div class="field">
                <div class="control">
                    <button class="button" onclick="window.paginate = null; window.searchProfiles();">{{ __('app.profile_search') }}</button>
                </div>
            </div>
        </div>

        <div class="is-default-padding-mobile" id="profilelist"></div>
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
        window.paginate = null;

        window.searchProfiles = function() {
            if (window.paginate === null) {
                document.getElementById('profilelist').innerHTML = '<div id="spinner"><center><i class="fas fa-spinner fa-spin"></i></center></div>';
            } else {
                document.getElementById('profilelist').innerHTML += '<div id="spinner"><center><i class="fas fa-spinner fa-spin"></i></center></div>';
            }

            if (document.getElementById('loadmore') !== null) {
                document.getElementById('loadmore').remove();
            }

            window.vue.ajaxRequest('post', '{{ url('/profilesearch') }}', { 
                    username: document.getElementById('search-username').value,
                    bio: document.getElementById('search-bio').value,
                    gender: document.getElementById('search-gender').value,
                    age_from: document.getElementById('search-age-from').value,
                    age_till: document.getElementById('search-age-till').value,
                    location: document.getElementById('search-location').value,
                    paginate: window.paginate
                }, function(response){
                    if (response.code == 200) {
                        if (document.getElementById('spinner') !== null) {
                            document.getElementById('spinner').remove();
                        }

                        if (response.data.length > 0) {
                            if (window.paginate === null) {
                                document.getElementById('profilelist').innerHTML = '';
                            }

                            response.data.forEach(function(elem, index) {
                                let html = window.renderProfileItem(elem);

                                document.getElementById('profilelist').innerHTML += html;
                            });

                            window.paginate = response.data[response.data.length - 1].id;

                            document.getElementById('profilelist').innerHTML += '<div id="loadmore"><center><a href="javascript:void(0);" onclick="window.searchProfiles();">{{ __('app.load_more') }}</a></center></div>';
                        } else {
                            if (window.paginate === null) {
                                document.getElementById('profilelist').innerHTML = '{{ __('app.profilesearch_no_users_found') }}';
                            } else {
                                document.getElementById('profilelist').innerHTML += '{{ __('app.profilesearch_no_users_found') }}';
                            }
                        }
                    }
            });
        }
    </script>
@endsection
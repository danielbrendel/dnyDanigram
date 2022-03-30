{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2022 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

<!--
@guest
    <div class="column is-5"></div>
    <div class="column is-5 is-sidespacing"></div>
@endguest

<div class="column is-2">
    <div class="@if ((!isset($inoverlay)) || (!$inoverlay)) favorites-nav @endif is-default-padding">
        <div>
            <div class="is-inline-block"><h2 class="is-default-headline-color">{{ __('app.favorites') }}</h2></div>
            @if ((isset($inoverlay)) && ($inoverlay))
                <div class="is-inline-block float-right is-margin-top-15"><a class="is-color-grey is-size-7" href="javascript:void(0);" onclick="window.toggleOverlay('favorites');">{{ __('app.close') }}</a></div>
            @endif
        </div>

        <div class="favorites-list" id="favorites-list">
            {!! __('app.login_for_favs') !!}
        </div>
    </div>
</div>
-->
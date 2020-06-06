{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

<div class="is-block">
    <div class="is-inline-block"><h2 class="is-default-headline-color">{{ __('app.popular_tags') }}</h2></div>
    @if ((isset($inoverlay)) && ($inoverlay))
        <div class="is-inline-block float-right is-margin-top-25 is-margin-right-15"><i class="fas fa-times is-pointer" onclick="window.toggleOverlay('popular-tags')"></i></div>
    @endif
</div>

<div>
    @foreach ($taglist as $tag)
        <div class="taglist-item is-block">
            <div class="taglist-item-left is-inline-block"><a href="{{ url('/t/' . $tag->tag) }}">#{{ \App\AppModel::getShortExpression($tag->tag) }}</a></div>
            <div class="taglist-item-right is-inline-block"><i class="far fa-heart"></i>&nbsp;{{ $tag->hearts }}</div>
        </div>
    @endforeach
</div>

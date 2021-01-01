{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2021 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

<div class="column is-2">
    <div class="@if ((!isset($inoverlay)) || (!$inoverlay)) populartags-nav @endif is-default-padding">
        <div class="is-block">
            <div class="is-inline-block"><h2 class="is-default-headline-color">{{ __('app.popular_tags') }}</h2></div>
            @if ((isset($inoverlay)) && ($inoverlay))
                <div class="is-inline-block float-right is-margin-top-15"><a class="is-color-grey is-size-7" href="javascript:void(0);" onclick="window.toggleOverlay('popular-tags')">{{ __('app.close') }}</a></div>
            @endif
        </div>

        <div>
            @foreach ($taglist as $tag)
                <div class="taglist-item is-block">
                    <div class="taglist-item-left is-inline-block">
                        <div class="taglist-item-left-image is-inline-block">
                            @if ($tag->top_image !== null)
                                <img src="{{ asset('gfx/posts/' . $tag->top_image) }}" width="32" height="32"/>&nbsp;&nbsp;
                            @else
                                &nbsp;&nbsp;&nbsp;<i class="fas fa-hashtag fa-lg"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            @endif
                        </div>

                        <div class="is-inline-block">
                            <div><a href="{{ url('/t/' . $tag->tag) }}">#{{ \App\AppModel::getShortExpression($tag->tag) }}</a></div>
                            <div>{{ __('app.stats_posts', ['count' => $tag->total_posts]) }}</div>
                        </div>
                    </div>

                    <div class="taglist-item-right is-inline-block"><i class="far fa-heart"></i>&nbsp;{{ $tag->hearts }}</div>
                </div>
            @endforeach
        </div>
    </div>
</div>

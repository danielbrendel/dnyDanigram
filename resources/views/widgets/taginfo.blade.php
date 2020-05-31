{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

<div class="userinfo-header">
    <div class="userinfo-image is-inline-block">
        <img src="{{ asset('gfx/posts/' . $tag->top_image) }}" width="24" height="24">
    </div>

    <div class="userinfo-name is-inline-block is-pointer">
        #{{ $tag->tag }}
    </div>
</div>

<div class="userinfo-bio">
    {{ __('app.tag_is_about', ['subject' => $tag->tag]) }}
</div>

<div class="userinfo-stats">
    <i class="far fa-calendar-alt" title="{{ $tag->created_at }}"></i>&nbsp;{{ __('app.created_at', ['date' => $tag->created_at->diffForHumans()]) }}<br/>
    <i class="far fa-file-image"></i>&nbsp;{{ __('app.stats_posts', ['count' => $tag->stats->posts]) }}<br/>
    <i class="far fa-comment"></i>&nbsp;{{ __('app.stats_comments', ['count' => $tag->stats->comments]) }}<br/>
    <i class="fas fa-heart"></i>&nbsp;{{ __('app.stats_hearts', ['count' => $tag->stats->hearts]) }}<br/>
</div>

{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

<div class="taginfo-header">
    <div class="taginfo-image is-inline-block">
        @if ($tag->top_image !== null)
            <img src="{{ asset('gfx/posts/' . $tag->top_image) }}" width="24" height="24">
        @else
            <i class="fas fa-hashtag fa-lg"></i>
        @endif
    </div>

    <div class="taginfo-name is-inline-block is-pointer">
        #{{ $tag->tag }}
    </div>
</div>

<div class="taginfo-about">
    {{ __('app.tag_is_about', ['subject' => $tag->tag]) }}
</div>

<div class="taginfo-stats">
    <i class="far fa-calendar-alt" title="{{ $tag->created_at }}"></i>&nbsp;{{ __('app.created_at', ['date' => $tag->created_at->diffForHumans()]) }}<br/>
    <i class="far fa-file-image"></i>&nbsp;{{ __('app.stats_posts', ['count' => $tag->stats->posts]) }}<br/>
    <i class="far fa-comment"></i>&nbsp;{{ __('app.stats_comments', ['count' => $tag->stats->comments]) }}<br/>
    <i class="fas fa-heart"></i>&nbsp;{{ __('app.stats_hearts', ['count' => $tag->stats->hearts]) }}<br/>
</div>

@auth
    <div class="taginfo-favorite favorite-ent_hashtag">
        @if ($favorited)
            <a href="javascript:void(0)" onclick="removeFavorite({{ $tag->id }}, 'ENT_HASHTAG', '{{ $tag->tag }}')">{{ __('app.favorite_remove') }}</a>
        @else
            <a href="javascript:void(0)" onclick="addFavorite({{ $tag->id }}, 'ENT_HASHTAG', '{{ $tag->tag }}')">{{ __('app.favorite_add') }}</a>
        @endif
    </div>
@endauth

@auth
    @if ($user->admin)
        <div class="taginfo-lock float-right">
            <a href="javascript:void(0)" onclick="lockHashtag({{ $tag->id }})">{{ __('app.lock_hashtag') }}</a>
        </div>
    @else
        <div class="taginfo-report float-right">
            <a href="javascript:void(0)" onclick="reportTag({{ $tag->id }})">{{ __('app.report_tag') }}</a>
        </div>
    @endif
@endauth

{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2022 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

@if ($hearted)
    <strong>{{ __('app.support_hashtag') }}<i id="heart-ent_hashtag-{{ $tag->id }}" class="fas fa-heart is-pointer is-hearted" onclick="window.vue.toggleHeart({{ $tag->id }}, 'ENT_HASHTAG')"></i> <span id="count-ent_hashtag-{{ $tag->id }}">{{ $heart_count }}</span></strong>
@else
    <strong>{{ __('app.support_hashtag') }}<i id="heart-ent_hashtag-{{ $tag->id }}" class="far fa-heart is-pointer" onclick="window.vue.toggleHeart({{ $tag->id }}, 'ENT_HASHTAG')"></i> <span id="count-ent_hashtag-{{ $tag->id }}">{{ $heart_count }}</span></strong>
@endif


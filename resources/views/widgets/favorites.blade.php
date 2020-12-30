{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

@if ($favorites === null) 
    <div class="column is-5"></div>
    <div class="column is-5 is-sidespacing"></div>
@endif

<div class="column is-2">
    <div class="@if ((!isset($inoverlay)) || (!$inoverlay)) favorites-nav @endif is-default-padding">
        <div>
            <div class="is-inline-block"><h2 class="is-default-headline-color">{{ __('app.favorites') }}</h2></div>
            @if ((isset($inoverlay)) && ($inoverlay))
                <div class="is-inline-block float-right is-margin-top-15"><a class="is-color-grey is-size-7" href="javascript:void(0);" onclick="window.toggleOverlay('favorites');">{{ __('app.close') }}</a></div>
            @endif
        </div>

        <div class="favorites-list">
            @if ($favorites !== null)
                @if (count($favorites) > 0)
                    @foreach ($favorites as $favorite)
                        <div class="favorites-item is-block favorite-item-{{ strtolower($favorite->type) }}-{{ $favorite->entityId }}">
                            <div class="favorites-item-left is-inline-block">
                                <div class="favorites-item-left-avatar">
                                    @if ($favorite->type === 'ENT_HASHTAG')
                                        @if ($favorite->avatar !== null)
                                            <img src="{{ asset('gfx/posts/' . $favorite->avatar) }}" width="32" height="32"/>
                                        @else
                                            &nbsp;<i class="fas fa-hashtag fa-lg"></i>&nbsp;&nbsp;
                                        @endif
                                    @elseif ($favorite->type === 'ENT_USER')
                                        <img src="{{ asset('gfx/avatars/' . $favorite->avatar) }}" width="32" height="32"/>
                                    @endif
                                </div>

                                <div class="favorites-item-left-info">
                                    <div class="">
                                        @if ($favorite->type === 'ENT_HASHTAG')
                                            <a href="{{ url('/t/' . $favorite->name) }}">#{{ $favorite->short_name }}</a>
                                        @elseif ($favorite->type === 'ENT_USER')
                                            <a href="{{ url('/u/' . $favorite->name) }}">{{ '@' . $favorite->short_name }}</a>
                                        @endif
                                    </div>

                                    <div class="is-color-grey">
                                        {{ __('app.stats_posts', ['count' => $favorite->total_posts]) }}
                                    </div>
                                </div>
                            </div>

                            <div class="favorites-item-right is-inline-block" onclick="deleteFavorite({{ $favorite->id }}, {{ $favorite->entityId }}, '{{ $favorite->type }}')"><i class="fas fa-times is-pointer" title="{{ __('app.remove') }}"></i></div>
                        </div>
                    @endforeach
                @else
                    <i class="has-no-favorites-yet">{{ __('app.no_favorites_yet') }}</i>
                @endif
            @else
                {!! __('app.login_for_favs') !!}
            @endif
        </div>
    </div>
</div>

{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

<div>
    <div class="is-inline-block"><h2 class="is-default-headline-color">{{ __('app.favorites') }}</h2></div>
    @if ((isset($inoverlay)) && ($inoverlay))
        <div class="is-inline-block float-right is-top-25"><i class="fas fa-times is-pointer" onclick="window.toggleOverlay('favorites')"></i></div>
    @endif
</div>

<div>
    @if (count($favorites) > 0)
        @foreach ($favorites as $favorite)
            <div class="taglist-item is-block" id="favorite-item-{{ $favorite->id }}">
                <div class="taglist-item-left is-inline-block">
                    @if ($favorite->type === 'ENT_HASHTAG')
                        <a href="{{ url('/t/' . $favorite->name) }}">#{{ $favorite->name }}</a>
                    @elseif ($favorite->type === 'ENT_USER')
                        <a href="{{ url('/u/' . $favorite->name) }}">{{ $favorite->name }}</a>
                    @endif
                </div>

                <div class="taglist-item-right is-inline-block"><i onclick="deleteFavorite({{ $favorite->id }}, {{ $favorite->entityId }}, '{{ $favorite->type }}')" class="fas fa-times is-pointer" title="{{ __('app.remove') }}"></i></div>
            </div>
        @endforeach
    @else
        <i>{{ __('app.no_favorites_yet') }}</i>
    @endif
</div>

<script>
    function deleteFavorite(id, eid, type)
    {
        window.vue.ajaxRequest('post', '{{ url('/f/remove') }}', { entityId: eid, entType: type }, function(response){
            if (response.code === 200) {
                document.getElementById('favorite-item-' + id).remove();
            }
        });
    }
</script>

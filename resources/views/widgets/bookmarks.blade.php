{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

<h2 class="is-default-headline-color">{{ __('app.bookmarks') }}</h2>

<div>
    @if (count($bookmarks) > 0)
        @foreach ($bookmarks as $bookmark)
            <div class="taglist-item is-block" id="bookmark-item-{{ $bookmark->id }}">
                <div class="taglist-item-left is-inline-block">
                    @if ($bookmark->type === 'ENT_HASHTAG')
                        <a href="{{ url('/t/' . $bookmark->entityId) }}">#{{ $bookmark->name }}</a>
                    @elseif ($bookmark->type === 'ENT_USER')
                        <a href="{{ url('/u/' . $bookmark->entityId) }}">{{ $bookmark->name }}</a>
                    @endif
                </div>

                <div class="taglist-item-right is-inline-block"><i onclick="deleteBookmark({{ $bookmark->id }}, {{ $bookmark->entityId }}, '{{ $bookmark->type }}')" class="fas fa-times is-pointer" title="{{ __('app.remove') }}"></i></div>
            </div>
        @endforeach
    @else
        <i>{{ __('app.no_bookmarks_yet') }}</i>
    @endif
</div>

<script>
    function deleteBookmark(id, eid, type)
    {
        window.vue.ajaxRequest('post', '{{ url('/b/remove') }}', { entityId: eid, entType: type }, function(response){
            if (response.code === 200) {
                document.getElementById('bookmark-item-' + id).remove();
            }
        });
    }
</script>

{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2022 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

<div>
    @if (env('TWITTER_NEWS', null) !== null)
        <a class="twitter-timeline" href="https://twitter.com/{{ env('TWITTER_NEWS') }}?ref_src=twsrc%5Etfw">Tweets by {{ env('TWITTER_NEWS') }}</a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
    @endif
</div>

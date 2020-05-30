{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

@extends('layouts.layout_member')

@section('body')
    <div class="column is-2 is-sidespacing"></div>

    <div class="column is-8">
        <div class="member-form">
            <div class="show-post-header">
                <div class="show-post-avatar">
                    <img src="{{ asset('gfx/avatars/' . $poster->avatar) }}" class="is-pointer" onclick="location.href='{{ url('/u/' . $poster->id) }}'">
                </div>

                <div class="show-post-userinfo">
                    <div>{{ __('app.posted_by', ['username' => $poster->username]) }}</div>
                    <div title="{{ $post->created_at }}">{{ __('app.posted_at', ['date' => $post->created_at->diffForHumans()]) }}</div>
                </div>
            </div>

            <div class="show-post-image">
                <img class="is-pointer" src="{{ asset('gfx/posts/' . $post->image_thumb) }}" onclick="window.open('{{ asset('gfx/posts/' . $post->image_full) }}')">
            </div>

            <div class="show-post-attributes">
                <div class="is-inline-block"><i class="fas fa-heart"></i> 1024</div>
                <div class="is-inline-block is-right" style="float:right;">234 comments</div>
            </div>

            <div class="show-post-description">
                {{ $post->description }}
            </div>

            <div class="show-post-hashtags">
                {{ $post->hashtags }}
            </div>
        </div>
    </div>

    <div class="column is-2 is-sidespacing"></div>
@endsection

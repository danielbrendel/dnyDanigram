{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2022 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

@extends('layouts.layout_feed')

@section('title', env('APP_PROJECTNAME') . ' - ' . __('app.messages'))

@section('body')
    <div class="column is-8">
        <h1>{{ __('app.message_create') }}</h1>

        <div class="member-form is-default-padding member-form-fixed-top">
            <form method="POST" action="{{ url('/messages/send') }}">
                @csrf

                <div class="field">
                    <label class="label">{{ __('app.username') }}</label>
                    <div class="control">
                        <input type="text" name="username" value="{{ $username }}">
                    </div>
                </div>

                <div class="field">
                    <label class="label">{{ __('app.subject') }}</label>
                    <div class="control">
                        <input type="text" name="subject" placeholder="{{ __('app.subject') }}" value="{{ old('subject') }}" required>
                    </div>
                </div>

                <div class="field">
                    <label class="label">{{ __('app.text') }}</label>
                    <div class="control">
                        <div id="input-text"></div>
                        <textarea name="text" id="post-text" class="is-hidden" placeholder="{{ __('app.type_something') }}">{{ old('text') }}</textarea>
                    </div>
                </div>

                <div class="field">
                    <input type="submit" value="{{ __('app.send') }}">
                </div>
            </form>
        </div>
    </div>

    <div class="column is-2 is-sidespacing"></div>
@endsection

@section('javascript')
    <script>
        var quillEditor = new Quill('#input-text', {
            theme: 'snow',
            placeholder: '{{ __('app.type_something') }}',
        });

        quillEditor.on('editor-change', function(eventName, ...args) {
            document.getElementById('post-text').value = quillEditor.root.innerHTML;
        });
    </script>
@endsection


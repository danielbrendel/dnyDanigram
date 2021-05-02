{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2021 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

@extends('layouts.layout_feed')

@section('title', env('APP_PROJECTNAME') . ' - ' . __('app.messages'))

@section('body')
    <div class="column is-8">
        <h1>{{ __('app.message_thread', ['name' => $msg->message_partner]) }}</h1>

        <div class="member-form is-default-padding member-form-fixed-top">
            <form method="POST" action="{{ url('/messages/send') }}">
                @csrf

                <input type="hidden" name="username" value="{{ $msg->message_partner }}">

                <div class="field">
                    <label class="label">{{ __('app.subject') }}</label>
                    <div class="control">
                        <input type="text" name="subject" value="{{ $msg->subject }}" required>
                    </div>
                </div>

                <div class="field">
                    <label class="label">{{ __('app.text') }}</label>
                    <div class="control">
                        <div id="input-text"></div>
                        <textarea name="text" id="post-text" class="is-hidden" placeholder="{{ __('app.type_something') }}"></textarea>
                    </div>
                </div>

                <div class="field">
                    <input type="submit" value="{{ __('app.send') }}">
                </div>
            </form>
        </div>

        <div id="message-thread-content"></div>

    <div class="column is-2 is-sidespacing"></div>

    </div>
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

        window.paginate = null;

        window.queryMessages = function() {
            let content = document.getElementById('message-thread-content');

            content.innerHTML += '<div id="spinner"><center><i class="fa fa-spinner fa-spin"></i></center></div>';

            let loadmore = document.getElementById('loadmore');
            if (loadmore) {
                loadmore.remove();
            }

            window.vue.ajaxRequest('post', '{{ url('/messages/query') }}', {
                id: '{{ $msg->channel }}',
                paginate: window.paginate
            },
            function(response) {
                if (response.code == 200) {
                    response.data.forEach(function(elem, index) {
                        let html = window.renderMessageItem(elem, {{ auth()->id() }});

                        content.innerHTML += html;
                    });

                    if (response.data.length > 0) {
                        window.paginate = response.data[response.data.length - 1].id;
                    }

                    let spinner = document.getElementById('spinner');
                    if (spinner) {
                        spinner.remove();
                    }

                    if (response.data.length > 0) {
                        content.innerHTML += '<div id="loadmore"><center><br/><i class="fas fa-arrow-down is-pointer" onclick="window.queryMessages();"></i></center></div>';
                    }
                } else {
                    console.error(response.msg);
                }
            });
        };

        document.addEventListener('DOMContentLoaded', function() {
            window.queryMessages();
        });
    </script>
@endsection

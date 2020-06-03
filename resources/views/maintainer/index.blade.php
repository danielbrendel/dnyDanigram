{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

@extends('layouts.layout_member')

@section('title', env('APP_PROJECTNAME') . ' - ' . __('app.maintainer_area'))

@section('body')
    <div class="column is-2 is-sidespacing"></div>

    <div class="column is-8">
        <div class="member-form is-default-padding">
            <div>
                <h1>{{ __('app.maintainer_area') }}</h1>
            </div>

            <div>
                <div><strong>{{ env('APP_NAME') }}</strong></div>
                <div><strong>Author: </strong>{{ env('APP_AUTHOR') }}</div>
                <div><strong>Codename: </strong>{{ env('APP_CODENAME') }}</div>
                <div><strong>Contact: </strong>{{ env('APP_CONTACT') }}</div>
                <div><strong>Version: </strong>{{ env('APP_VERSION') }}</div>
                <br/>
            </div>

            <ul data-role="tabs" data-expand="true">
                <li><a href="#tab-page-1">{{ __('app.index_content') }}</a></li>
                <li><a href="#tab-page-2">{{ __('app.cookie_consent') }}</a></li>
                <li><a href="#tab-page-3">{{ __('app.about') }}</a></li>
                <li><a href="#tab-page-4">{{ __('app.imprint') }}</a></li>
                <li><a href="#tab-page-5">{{ __('app.tos') }}</a></li>
                <li><a href="#tab-page-6">{{ __('app.reg_info') }}</a></li>
                <li><a href="#tab-page-7">{{ __('app.faq') }}</a></li>
                <li><a href="#tab-page-8">{{ __('app.environment') }}</a></li>
                <li><a href="#tab-page-9">{{ __('app.users') }}</a></li>
                <li><a href="#tab-page-10">{{ __('app.newsletter') }}</a></li>
                <li><a href="#tab-page-11">{{ __('app.custom_css') }}</a></li>
                <li><a href="#tab-page-12">{{ __('app.favicon') }}</a></li>
            </ul>
            <div class="border bd-default no-border-top p-2">
                <div id="tab-page-1">
                    <form method="POST" action="{{ url('/maintainer/save') }}">
                        @csrf

                        <input type="hidden" name="attribute" value="home_index_content">

                        <div class="field">
                            <label class="label">{{ __('app.index_content_description') }}</label>
                            <div class="control">
                                <textarea class="textarea" name="content">{{ $settings->home_index_content }}</textarea>
                            </div>
                        </div>

                        <div class="field">
                            <div class="control">
                                <input type="submit" value="{{ __('app.save') }}">
                            </div>
                        </div>
                    </form>
                </div>

                <div id="tab-page-2">
                    <form method="POST" action="{{ url('/maintainer/save') }}">
                        @csrf

                        <input type="hidden" name="attribute" value="cookie_consent">

                        <div class="field">
                            <label class="label">{{ __('app.cookie_consent_description') }}</label>
                            <div class="control">
                                <textarea class="textarea" name="content">{{ $settings->cookie_consent }}</textarea>
                            </div>
                        </div>

                        <div class="field">
                            <div class="control">
                                <input type="submit" value="{{ __('app.save') }}">
                            </div>
                        </div>
                    </form>
                </div>

                <div id="tab-page-3">
                    <form method="POST" action="{{ url('/maintainer/save') }}">
                        @csrf

                        <input type="hidden" name="attribute" value="about">

                        <div class="field">
                            <label class="label">{{ __('app.about_description') }}</label>
                            <div class="control">
                                <textarea class="textarea" name="content">{{ $settings->about }}</textarea>
                            </div>
                        </div>

                        <div class="field">
                            <div class="control">
                                <input type="submit" value="{{ __('app.save') }}">
                            </div>
                        </div>
                    </form>
                </div>

                <div id="tab-page-4">
                    <form method="POST" action="{{ url('/maintainer/save') }}">
                        @csrf

                        <input type="hidden" name="attribute" value="imprint">

                        <div class="field">
                            <label class="label">{{ __('app.imprint_description') }}</label>
                            <div class="control">
                                <textarea class="textarea" name="content">{{ $settings->imprint }}</textarea>
                            </div>
                        </div>

                        <div class="field">
                            <div class="control">
                                <input type="submit" value="{{ __('app.save') }}">
                            </div>
                        </div>
                    </form>
                </div>

                <div id="tab-page-5">
                    <form method="POST" action="{{ url('/maintainer/save') }}">
                        @csrf

                        <input type="hidden" name="attribute" value="tos">

                        <div class="field">
                            <label class="label">{{ __('app.tos_description') }}</label>
                            <div class="control">
                                <textarea class="textarea" name="content">{{ $settings->tos }}</textarea>
                            </div>
                        </div>

                        <div class="field">
                            <div class="control">
                                <input type="submit" value="{{ __('app.save') }}">
                            </div>
                        </div>
                    </form>
                </div>

                <div id="tab-page-6">
                    <form method="POST" action="{{ url('/maintainer/save') }}">
                        @csrf

                        <input type="hidden" name="attribute" value="reg_info">

                        <div class="field">
                            <label class="label">{{ __('app.reg_info_description') }}</label>
                            <div class="control">
                                <textarea class="textarea" name="content">{{ $settings->reg_info }}</textarea>
                            </div>
                        </div>

                        <div class="field">
                            <div class="control">
                                <input type="submit" value="{{ __('app.save') }}">
                            </div>
                        </div>
                    </form>
                </div>

                <div id="tab-page-7">
                    <table class="table striped table-border mt-4" data-role="table" data-pagination="true"
                           data-table-rows-count-title="{{ __('app.table_show_entries') }}"
                           data-table-search-title="{{ __('app.table_search') }}"
                           data-table-info-title="{{ __('app.table_row_info') }}"
                           data-pagination-prev-title="{{ __('app.table_pagination_prev') }}"
                           data-pagination-next-title="{{ __('app.table_pagination_next') }}">
                        <thead>
                        <tr>
                            <th class="text-left">{{ __('app.faq_id') }}</th>
                            <th class="text-left">{{ __('app.faq_question') }}</th>
                            <th class="text-left">{{ __('app.faq_answer') }}</th>
                            <th class="text-left">{{ __('app.faq_last_updated') }}</th>
                            <th class="text-right">{{ __('app.remove') }}</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($faqs as $faq)
                            <tr>
                                <td>
                                    #{{ $faq->id }}
                                </td>

                                <td class="right">
                                    <a href="javascript:void(0)" onclick="document.getElementById('faq-id').value = {{ $faq->id }}; document.getElementById('faq-question').value = '{{ $faq->question }}'; document.getElementById('faq-answer').value = '{{ $faq->answer }}'; vue.bShowEditFaq = true;" title="{{ __('app.faq_edit') }}">{{ $faq->question }}</a>
                                </td>

                                <td>
                                    <?php
                                    if (strlen($faq->answer) > 20) {
                                        echo substr($faq->answer, 0, 20) . '...';
                                    } else {
                                        echo $faq->answer;
                                    }
                                    ?>
                                </td>

                                <td><div title="{{ $faq->updated_at }}">{{ $faq->updated_at->diffForHumans() }}</div></td>

                                <td>
                                    <a href="javascript:void(0)" onclick="if (confirm('{{ __('app.faq_remove_confirm') }}')) location.href = '{{ url('/maintainer/faq/' . $faq->id . '/remove') }}';">{{ __('app.faq_remove') }}</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="modal" :class="{'is-active': bShowCreateFaq}">
                        <div class="modal-background"></div>
                        <div class="modal-card is-top-25">
                            <header class="modal-card-head is-stretched">
                                <p class="modal-card-title">{{ __('app.faq_create') }}</p>
                                <button class="delete" aria-label="close" onclick="vue.bShowCreateFaq = false;"></button>
                            </header>
                            <section class="modal-card-body is-stretched">
                                <form method="POST" action="{{ url('/maintainer/faq/create') }}" id="formCreateFaq">
                                    @csrf

                                    <div class="field is-stretched">
                                        <label class="label">{{ __('app.faq_question') }}</label>
                                        <div class="control">
                                            <input type="text" name="question">
                                        </div>
                                    </div>

                                    <div class="field is-stretched">
                                        <label class="label">{{ __('app.faq_answer') }}</label>
                                        <div class="control">
                                            <textarea name="answer"></textarea>
                                        </div>
                                    </div>
                                </form>
                            </section>
                            <footer class="modal-card-foot is-stretched">
                                <button class="button is-success" onclick="document.getElementById('formCreateFaq').submit();">{{ __('app.save') }}</button>
                                <button class="button" onclick="vue.bShowCreateFaq = false;">{{ __('app.cancel') }}</button>
                            </footer>
                        </div>
                    </div>

                    <div class="modal" :class="{'is-active': bShowEditFaq}">
                        <div class="modal-background"></div>
                        <div class="modal-card is-top-25">
                            <header class="modal-card-head is-stretched">
                                <p class="modal-card-title">{{ __('app.edit_faq') }}</p>
                                <button class="delete" aria-label="close" onclick="vue.bShowEditFaq = false;"></button>
                            </header>
                            <section class="modal-card-body is-stretched">
                                <form method="POST" action="{{ url('/maintainer/faq/edit') }}" id="formEditFaq">
                                    @csrf

                                    <input type="hidden" name="id" id="faq-id">

                                    <div class="field is-stretched">
                                        <label class="label">{{ __('app.faq_question') }}</label>
                                        <div class="control">
                                            <input type="text" name="question" id="faq-question">
                                        </div>
                                    </div>

                                    <div class="field is-stretched">
                                        <label class="label">{{ __('app.faq_answer') }}</label>
                                        <div class="control">
                                            <textarea name="answer" id="faq-answer"></textarea>
                                        </div>
                                    </div>
                                </form>
                            </section>
                            <footer class="modal-card-foot is-stretched">
                                <button class="button is-success" onclick="document.getElementById('formEditFaq').submit();">{{ __('app.save') }}</button>
                                <button class="button" onclick="vue.bShowEditFaq = false;">{{ __('app.cancel') }}</button>
                            </footer>
                        </div>
                    </div>

                    <br/>

                    <center><a class="button" href="javascript:void(0)" onclick="location.reload();">{{ __('app.refresh') }}</a>&nbsp;&nbsp;&nbsp;&nbsp;
                    <a class="button is-success" onclick="vue.bShowCreateFaq = true;">{{ __('app.create') }}</a></center><br/>
                </div>

                <div id="tab-page-8">
                    <form method="POST" action="{{ url('/maintainer/env/save') }}">
                        @csrf

                        <div class="field">
                            <label class="label">{{ __('app.project_name') }}</label>
                            <div class="control">
                                <input type="text" name="ENV_APP_PROJECTNAME" value="{{ env('APP_PROJECTNAME') }}">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.project_description') }}</label>
                            <div class="control">
                                <input type="text" name="ENV_APP_DESCRIPTION" value="{{ env('APP_DESCRIPTION') }}">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.project_tags') }}</label>
                            <div class="control">
                                <input type="text" name="ENV_APP_TAGS" value="{{ env('APP_TAGS') }}">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.project_division') }}</label>
                            <div class="control">
                                <input type="text" name="ENV_APP_DIVISION" value="{{ env('APP_DIVISION') }}">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.project_lang') }}</label>
                            <div class="control">
                                <select name="ENV_APP_LANG">
                                    @foreach ($langs as $lang)
                                        <option value="{{ $lang }}" @if ($lang === env('APP_LANG')) {{ 'selected' }} @endif>{{ $lang }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.project_smtp_host') }}</label>
                            <div class="control">
                                <input type="text" name="ENV_SMTP_HOST" value="{{ env('SMTP_HOST') }}">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.project_smtp_user') }}</label>
                            <div class="control">
                                <input type="text" name="ENV_SMTP_USERNAME" value="{{ env('SMTP_USERNAME') }}">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.project_smtp_pw') }}</label>
                            <div class="control">
                                <input type="text" name="ENV_SMTP_PASSWORD" value="{{ env('SMTP_PASSWORD') }}">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.project_smtp_fromname') }}</label>
                            <div class="control">
                                <input type="text" name="ENV_SMTP_FROMNAME" value="{{ env('SMTP_FROMNAME') }}">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.project_smtp_fromaddress') }}</label>
                            <div class="control">
                                <input type="text" name="ENV_SMTP_FROMADDRESS" value="{{ env('SMTP_FROMADDRESS') }}">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.project_ga') }}</label>
                            <div class="control">
                                <input type="text" name="ENV_GA_TOKEN" value="{{ env('GA_TOKEN') }}">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.project_twitter_news') }}</label>
                            <div class="control">
                                <input type="text" name="ENV_TWITTER_NEWS" value="{{ env('TWITTER_NEWS') }}">
                            </div>
                        </div>

                        <div class="field">
                            <div class="control">
                                <input type="submit" value="{{ __('app.save') }}">
                            </div>
                        </div>
                    </form>
                </div>

                <div id="tab-page-9">
                    <div class="field">
                        <input type="text" id="userident">
                    </div>

                    <div class="field">
                        <input type="button" value="{{ __('app.get_user_details') }}" onclick="getUserDetails(document.getElementById('userident').value);">
                    </div>

                    <div id="user_settings" class="is-hidden">
                        <form method="POST" action="{{ url('/maintainer/u/save') }}">
                            @csrf

                            <input type="hidden" name="id" id="user_id">

                            <div class="field">
                                <label class="label">{{ __('app.username') }}</label>
                                <div class="control">
                                    <input type="text" id="user_name" name="username">
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">{{ __('app.email') }}</label>
                                <div class="control">
                                    <input type="text" name="email" id="user_email">
                                </div>
                            </div>

                            <div class="field">
                                <div class="control">
                                    <input type class="checkbox" name="deactivated" id="user_deactivated" data-role="checkbox" data-style="2" data-caption="{{ __('app.deactivated') }}" value="1">
                                </div>
                            </div>

                            <div class="field">
                                <div class="control">
                                    <input type class="checkbox" name="admin" id="user_admin" data-role="checkbox" data-style="2" data-caption="{{ __('app.admin') }}" value="1">
                                </div>
                            </div>

                            <div class="field">
                                <div class="control">
                                    <input type class="checkbox" name="maintainer" id="user_maintainer" data-role="checkbox" data-style="2" data-caption="{{ __('app.maintainer') }}" value="1">
                                </div>
                            </div>

                            <div class="field">
                                <div class="control">
                                    <input type="submit" value="{{ __('app.save') }}">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div id="tab-page-10">
                    <div>
                        <form method="POST" action="{{ url('/maintainer/newsletter') }}">
                            @csrf

                            <div class="field">
                                <label class="label">{{ __('app.subject') }}</label>
                                <div class="control">
                                    <input type="text" name="subject">
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">{{ __('app.text') }}</label>
                                <div class="control">
                                    <textarea name="content"></textarea>
                                </div>
                            </div>

                            <div class="field">
                                <div class="control">
                                    <input type="submit" value="{{ __('app.send') }}">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div id="tab-page-11">
                    <form method="POST" action="{{ url('/maintainer/css/save') }}">
                        @csrf

                        <div class="field">
                            <label class="label">{{ __('app.custom_css_code') }}</label>
                            <div class="control">
                                <textarea class="textarea" name="code">{{ $custom_css }}</textarea>
                            </div>
                        </div>

                        <div class="field">
                            <div class="control">
                                <input type="submit" value="{{ __('app.save') }}">
                            </div>
                        </div>
                    </form>
                </div>

                <div id="tab-page-12">
                    <form method="POST" action="{{ url('/maintainer/favicon/save') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="field">
                            <label class="label">{{ __('app.favicon_info') }}</label>
                            <div class="control">
                                <div><img src="{{ url('/favicon.png') }}" alt="favicon"></div>
                                <div><input type="file" name="favicon" data-role="file"></div>
                            </div>
                        </div>

                        <div class="field">
                            <div class="control">
                                <input type="submit" value="{{ __('app.save') }}">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <br/><br/>
    </div>

    <div class="column is-2 is-sidespacing"></div>
@endsection

@section('javascript')
    <script>
        function getUserDetails(ident)
        {
            window.vue.ajaxRequest('get', '{{ url('/maintainer/u/details') }}?ident=' + ident, {}, function(response) {
                if (response.code === 200) {
                    document.getElementById('user_settings').classList.remove('is-hidden');

                    document.getElementById('user_id').value = response.data.id;

                    document.getElementById('user_name').value = response.data.username;
                    document.getElementById('user_email').value = response.data.email;

                    document.getElementById('user_deactivated').checked = response.data.deactivated;
                    document.getElementById('user_admin').checked = response.data.admin;
                    document.getElementById('user_maintainer').checked = response.data.admin;
                } else {
                    document.getElementById('user_settings').classList.add('is-hidden');
                    alert(response.msg);
                }
            });
        }
    </script>
@endsection
{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2022 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

@extends('layouts.layout_feed')

@section('title', env('APP_PROJECTNAME') . ' - ' . __('app.maintainer_area'))

@section('body')
    <div class="column is-8">
        <div class="member-form is-default-padding member-form-fixed-top">
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
                <li><a href="#tab-page-11">{{ __('app.themes') }}</a></li>
                <li><a href="#tab-page-12">{{ __('app.logo') }}</a></li>
                <li><a href="#tab-page-13">{{ __('app.reports') }}</a></li>
                <li><a href="#tab-page-14">{{ __('app.welcome_content') }}</a></li>
                <li><a href="#tab-page-15">{{ __('app.project_name_formatted') }}</a></li>
                <li><a href="#tab-page-16">{{ __('app.head_code') }}</a></li>
                <li><a href="#tab-page-17">{{ __('app.adcode') }}</a></li>
                <li><a href="#tab-page-18">{{ __('app.profile_items') }}</a></li>
                <li><a href="#tab-page-19">{{ __('app.forums') }}</a></li>
                <li><a href="#tab-page-20">{{ __('app.categories') }}</a></li>
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
                            <label class="label">{{ __('app.project_public_feed') }}</label>
                            <div class="control">
                                <input name="ENV_APP_PUBLICFEED" type="checkbox" value="1" data-role="checkbox" data-type="2" data-caption="{{ __('app.project_public_feed') }}" @if (env('APP_PUBLICFEED')) {{ 'checked' }} @endif>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.project_categories') }}</label>
                            <div class="control">
                                <input name="ENV_APP_ENABLECATEGORIES" type="checkbox" value="1" data-role="checkbox" data-type="2" data-caption="{{ __('app.project_categories') }}" @if (env('APP_ENABLECATEGORIES')) {{ 'checked' }} @endif>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.nsfw_enable_filter') }}</label>
                            <div class="control">
                                <input name="ENV_APP_ENABLENSFWFILTER" type="checkbox" value="1" data-role="checkbox" data-type="2" data-caption="{{ __('app.nsfw_enable_filter') }}" @if (env('APP_ENABLENSFWFILTER')) {{ 'checked' }} @endif>
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
                            <label class="label">{{ __('app.project_helprealm_workspace') }}</label>
                            <div class="control">
                                <input type="text" name="ENV_HELPREALM_WORKSPACE" value="{{ env('HELPREALM_WORKSPACE') }}">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.project_helprealm_token') }}</label>
                            <div class="control">
                                <input type="text" name="ENV_HELPREALM_TOKEN" value="{{ env('HELPREALM_TOKEN') }}">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.project_helprealm_tickettypeid') }}</label>
                            <div class="control">
                                <input type="text" name="ENV_HELPREALM_TICKETTYPEID" value="{{ env('HELPREALM_TICKETTYPEID') }}">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.project_stripe_enable') }}</label>
                            <div class="control">
                                <input name="ENV_STRIPE_ENABLE" type="checkbox" value="1" data-role="checkbox" data-type="2" data-caption="{{ __('app.project_stripe_enable') }}" @if (env('STRIPE_ENABLE')) {{ 'checked' }} @endif>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.project_stripe_secret') }}</label>
                            <div class="control">
                                <input type="text" name="ENV_STRIPE_TOKEN_SECRET" value="{{ env('STRIPE_TOKEN_SECRET') }}">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.project_stripe_public') }}</label>
                            <div class="control">
                                <input type="text" name="ENV_STRIPE_TOKEN_PUBLIC" value="{{ env('STRIPE_TOKEN_PUBLIC') }}">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.project_stripe_costs_value') }}</label>
                            <div class="control">
                                <input type="number" name="ENV_STRIPE_COSTS_VALUE" value="{{ env('STRIPE_COSTS_VALUE') }}">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.project_stripe_costs_label') }}</label>
                            <div class="control">
                                <input type="text" name="ENV_STRIPE_COSTS_LABEL" value="{{ env('STRIPE_COSTS_LABEL') }}">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.project_upload_file_size_label') }}</label>
                            <div class="control">
                                <input type="number" name="ENV_APP_MAXUPLOADSIZE" value="{{ env('APP_MAXUPLOADSIZE') }}">
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
                    <table class="table striped table-border mt-4" data-role="table" data-pagination="true"
                           data-table-rows-count-title="{{ __('app.table_show_entries') }}"
                           data-table-search-title="{{ __('app.table_search') }}"
                           data-table-info-title="{{ __('app.table_row_info') }}"
                           data-pagination-prev-title="{{ __('app.table_pagination_prev') }}"
                           data-pagination-next-title="{{ __('app.table_pagination_next') }}">
                        <thead>
                        <tr>
                            <th class="text-left">{{ __('app.theme_name') }}</th>
                            <th class="text-left">{{ __('app.theme_default') }}</th>
                            <th class="text-left">{{ __('app.theme_edit') }}</th>
                            <th class="text-right">{{ __('app.theme_delete') }}</th>
                            <th class="is-hidden"></th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($themes as $theme)
                            <tr>
                                <td>
                                    {{ $theme->name }}

                                    @if (\App\AppModel::getDefaultTheme() === $theme->name)
                                        <span>&nbsp;&dash;&nbsp;{{ __('app.selected') }}</span>
                                    @endif
                                </td>

                                <td class="text-right">
                                    <a href="javascript:void(0)" onclick="location.href = '{{ url('/maintainer/themes/setdefault?name=' . $theme->name) }}';">{{ __('app.theme_default') }}</a>
                                </td>

                                <td>
                                    <a href="javascript:void(0)" onclick="document.getElementById('theme_name').value = '{{ $theme->name }}'; document.getElementById('theme_code').value = document.getElementById('td-theme-code-{{ $theme->name }}').value; window.vue.bShowEditTheme = true;">{{ __('app.theme_edit') }}</a>
                                </td>

                                <td class="text-right">
                                    <a href="javascript:void(0)" onclick="if (confirm('{{ __('app.theme_confirm_delete') }}')) location.href = '{{ url('/maintainer/themes/delete?name=' . $theme->name) }}';">{{ __('app.theme_delete') }}</a>
                                </td>

                                <td><textarea id="td-theme-code-{{ $theme->name }}" class="is-hidden">{{ $theme->content }}</textarea></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <br/>

                    <div class="field">
                        <div class="control">
                            <button type="button" class="button is-primary" onclick="window.vue.bShowCreateTheme = true;">{{ __('app.theme_create') }}</button>&nbsp;
                            <button type="button" class="button" onclick="location.href = '{{ url('/maintainer/themes/setdefault?name=_default') }}';">{{ __('app.theme_reset_default') }}</button>
                        </div>
                    </div>

                    <br/>
                </div>

                <div id="tab-page-12">
                    <form method="POST" action="{{ url('/maintainer/logo/save') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="field">
                            <label class="label">{{ __('app.logo_info') }}</label>
                            <div class="control">
                                <div><img src="{{ url('/logo.png') }}" alt="logo"></div>
                                <div><input type="file" name="logo" data-role="file"></div>
                            </div>
                        </div>

                        <div class="field">
                            <div class="control">
                                <input type="submit" value="{{ __('app.save') }}">
                            </div>
                        </div>
                    </form>
                </div>

                <div id="tab-page-13">
                    <table class="table striped table-border mt-4" data-role="table" data-pagination="true"
                           data-table-rows-count-title="{{ __('app.table_show_entries') }}"
                           data-table-search-title="{{ __('app.table_search') }}"
                           data-table-info-title="{{ __('app.table_row_info') }}"
                           data-pagination-prev-title="{{ __('app.table_pagination_prev') }}"
                           data-pagination-next-title="{{ __('app.table_pagination_next') }}">
                        <thead>
                        <tr>
                            <th class="text-left">{{ __('app.report_id') }}</th>
                            <th class="text-left">{{ __('app.report_entity') }}</th>
                            <th class="text-left">{{ __('app.report_type') }}</th>
                            <th class="text-left">{{ __('app.report_count') }}</th>
                            <th class="text-right">{{ __('app.report_lock') }}</th>
                            <th class="text-right">{{ __('app.report_delete') }}</th>
                            <th class="text-right">{{ __('app.report_safe') }}</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($reports['posts'] as $item)
                            <tr>
                                <td>
                                    #{{ $item->id }}
                                </td>

                                <td class="right">
                                    <a href="{{ url('/p/' . $item->entityId) }}" target="_blank">{{ $item->entityId }}</a>
                                </td>

                                <td>
                                    {{ $item->type }}
                                </td>

                                <td>{{ $item->count }}</td>

                                <td>
                                    <a href="javascript:void(0)" onclick="if (confirm('{{ __('app.report_confirm_lock') }}')) location.href = '{{ url('/maintainer/entity/lock?id=' . $item->entityId . '&type=' . $item->type) }}';">{{ __('app.report_lock') }}</a>
                                </td>

                                <td>
                                    <a href="javascript:void(0)" onclick="if (confirm('{{ __('app.report_confirm_delete') }}')) location.href = '{{ url('/maintainer/entity/delete?id=' . $item->entityId . '&type=' . $item->type) }}';">{{ __('app.report_delete') }}</a>
                                </td>

                                <td>
                                    <a href="javascript:void(0)" onclick="if (confirm('{{ __('app.report_confirm_safe') }}')) location.href = '{{ url('/maintainer/entity/safe?id=' . $item->entityId . '&type=' . $item->type) }}';">{{ __('app.report_safe') }}</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <table class="table striped table-border mt-4" data-role="table" data-pagination="true"
                           data-table-rows-count-title="{{ __('app.table_show_entries') }}"
                           data-table-search-title="{{ __('app.table_search') }}"
                           data-table-info-title="{{ __('app.table_row_info') }}"
                           data-pagination-prev-title="{{ __('app.table_pagination_prev') }}"
                           data-pagination-next-title="{{ __('app.table_pagination_next') }}">
                        <thead>
                        <tr>
                            <th class="text-left">{{ __('app.report_id') }}</th>
                            <th class="text-left">{{ __('app.report_entity') }}</th>
                            <th class="text-left">{{ __('app.report_type') }}</th>
                            <th class="text-left">{{ __('app.report_count') }}</th>
                            <th class="text-right">{{ __('app.report_lock') }}</th>
                            <th class="text-right">{{ __('app.report_delete') }}</th>
                            <th class="text-right">{{ __('app.report_safe') }}</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($reports['users'] as $item)
                            <tr>
                                <td>
                                    #{{ $item->id }}
                                </td>

                                <td class="right">
                                    <a href="{{ url('/u/' . $item->entityId) }}" target="_blank">{{ $item->entityId }}</a>
                                </td>

                                <td>
                                    {{ $item->type }}
                                </td>

                                <td>{{ $item->count }}</td>

                                <td>
                                    <a href="javascript:void(0)" onclick="if (confirm('{{ __('app.report_confirm_lock') }}')) location.href = '{{ url('/maintainer/entity/lock?id=' . $item->entityId . '&type=' . $item->type) }}';">{{ __('app.report_lock') }}</a>
                                </td>

                                <td>
                                    <a href="javascript:void(0)" onclick="if (confirm('{{ __('app.report_confirm_delete') }}')) location.href = '{{ url('/maintainer/entity/delete?id=' . $item->entityId . '&type=' . $item->type) }}';">{{ __('app.report_delete') }}</a>
                                </td>

                                <td>
                                    <a href="javascript:void(0)" onclick="if (confirm('{{ __('app.report_confirm_safe') }}')) location.href = '{{ url('/maintainer/entity/safe?id=' . $item->entityId . '&type=' . $item->type) }}';">{{ __('app.report_safe') }}</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <table class="table striped table-border mt-4" data-role="table" data-pagination="true"
                           data-table-rows-count-title="{{ __('app.table_show_entries') }}"
                           data-table-search-title="{{ __('app.table_search') }}"
                           data-table-info-title="{{ __('app.table_row_info') }}"
                           data-pagination-prev-title="{{ __('app.table_pagination_prev') }}"
                           data-pagination-next-title="{{ __('app.table_pagination_next') }}">
                        <thead>
                        <tr>
                            <th class="text-left">{{ __('app.report_id') }}</th>
                            <th class="text-left">{{ __('app.report_entity') }}</th>
                            <th class="text-left">{{ __('app.report_type') }}</th>
                            <th class="text-left">{{ __('app.report_count') }}</th>
                            <th class="text-right">{{ __('app.report_lock') }}</th>
                            <th class="text-right">{{ __('app.report_delete') }}</th>
                            <th class="text-right">{{ __('app.report_safe') }}</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($reports['comments'] as $item)
                            <tr>
                                <td>
                                    #{{ $item->id }}
                                </td>

                                <td class="right">
                                    <a href="{{ url('/p/' . $item->postId . '?c=' . $item->entityId . '#' . $item->entityId) }}" target="_blank">{{ $item->entityId }}</a>
                                </td>

                                <td>
                                    {{ $item->type }}
                                </td>

                                <td>{{ $item->count }}</td>

                                <td>
                                    <a href="javascript:void(0)" onclick="if (confirm('{{ __('app.report_confirm_lock') }}')) location.href = '{{ url('/maintainer/entity/lock?id=' . $item->entityId . '&type=' . $item->type) }}';">{{ __('app.report_lock') }}</a>
                                </td>

                                <td>
                                    <a href="javascript:void(0)" onclick="if (confirm('{{ __('app.report_confirm_delete') }}')) location.href = '{{ url('/maintainer/entity/delete?id=' . $item->entityId . '&type=' . $item->type) }}';">{{ __('app.report_delete') }}</a>
                                </td>

                                <td>
                                    <a href="javascript:void(0)" onclick="if (confirm('{{ __('app.report_confirm_safe') }}')) location.href = '{{ url('/maintainer/entity/safe?id=' . $item->entityId . '&type=' . $item->type) }}';">{{ __('app.report_safe') }}</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <table class="table striped table-border mt-4" data-role="table" data-pagination="true"
                           data-table-rows-count-title="{{ __('app.table_show_entries') }}"
                           data-table-search-title="{{ __('app.table_search') }}"
                           data-table-info-title="{{ __('app.table_row_info') }}"
                           data-pagination-prev-title="{{ __('app.table_pagination_prev') }}"
                           data-pagination-next-title="{{ __('app.table_pagination_next') }}">
                        <thead>
                        <tr>
                            <th class="text-left">{{ __('app.report_id') }}</th>
                            <th class="text-left">{{ __('app.report_entity') }}</th>
                            <th class="text-left">{{ __('app.report_type') }}</th>
                            <th class="text-left">{{ __('app.report_count') }}</th>
                            <th class="text-right">{{ __('app.report_lock') }}</th>
                            <th class="text-right">{{ __('app.report_delete') }}</th>
                            <th class="text-right">{{ __('app.report_safe') }}</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($reports['hashtags'] as $item)
                            <tr>
                                <td>
                                    #{{ $item->id }}
                                </td>

                                <td class="right">
                                    <a href="{{ url('/t/' . $item->entityId) }}" target="_blank">{{ $item->entityId }}</a>
                                </td>

                                <td>
                                    {{ $item->type }}
                                </td>

                                <td>{{ $item->count }}</td>

                                <td>
                                    <a href="javascript:void(0)" onclick="if (confirm('{{ __('app.report_confirm_lock') }}')) location.href = '{{ url('/maintainer/entity/lock?id=' . $item->entityId . '&type=' . $item->type) }}';">{{ __('app.report_lock') }}</a>
                                </td>

                                <td>
                                    <a href="javascript:void(0)" onclick="if (confirm('{{ __('app.report_confirm_delete') }}')) location.href = '{{ url('/maintainer/entity/delete?id=' . $item->entityId . '&type=' . $item->type) }}';">{{ __('app.report_delete') }}</a>
                                </td>

                                <td>
                                    <a href="javascript:void(0)" onclick="if (confirm('{{ __('app.report_confirm_safe') }}')) location.href = '{{ url('/maintainer/entity/safe?id=' . $item->entityId . '&type=' . $item->type) }}';">{{ __('app.report_safe') }}</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <table class="table striped table-border mt-4" data-role="table" data-pagination="true"
                           data-table-rows-count-title="{{ __('app.table_show_entries') }}"
                           data-table-search-title="{{ __('app.table_search') }}"
                           data-table-info-title="{{ __('app.table_row_info') }}"
                           data-pagination-prev-title="{{ __('app.table_pagination_prev') }}"
                           data-pagination-next-title="{{ __('app.table_pagination_next') }}">
                        <thead>
                        <tr>
                            <th class="text-left">{{ __('app.report_id') }}</th>
                            <th class="text-left">{{ __('app.report_entity') }}</th>
                            <th class="text-left">{{ __('app.report_type') }}</th>
                            <th class="text-left">{{ __('app.report_count') }}</th>
                            <th class="text-right">{{ __('app.report_lock') }}</th>
                            <th class="text-right">{{ __('app.report_delete') }}</th>
                            <th class="text-right">{{ __('app.report_safe') }}</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($reports['forum_posts'] as $item)
                            <tr>
                                <td>
                                    #{{ $item->id }}
                                </td>

                                <td class="right">
                                    <a href="{{ url('/forum/thread/post/' . $item->entityId . '/show') }}" target="_blank">{{ $item->entityId }}</a>
                                </td>

                                <td>
                                    {{ $item->type }}
                                </td>

                                <td>{{ $item->count }}</td>

                                <td>
                                    <a href="javascript:void(0)" onclick="if (confirm('{{ __('app.report_confirm_lock') }}')) location.href = '{{ url('/maintainer/entity/lock?id=' . $item->entityId . '&type=' . $item->type) }}';">{{ __('app.report_lock') }}</a>
                                </td>

                                <td>
                                    <a href="javascript:void(0)" onclick="if (confirm('{{ __('app.report_confirm_delete') }}')) location.href = '{{ url('/maintainer/entity/delete?id=' . $item->entityId . '&type=' . $item->type) }}';">{{ __('app.report_delete') }}</a>
                                </td>

                                <td>
                                    <a href="javascript:void(0)" onclick="if (confirm('{{ __('app.report_confirm_safe') }}')) location.href = '{{ url('/maintainer/entity/safe?id=' . $item->entityId . '&type=' . $item->type) }}';">{{ __('app.report_safe') }}</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div id="tab-page-14">
                    <form method="POST" action="{{ url('/maintainer/welcomecontent') }}">
                        @csrf

                        <div class="field">
                            <label class="label">{{ __('app.welcome_content') }}</label>
                            <div class="control">
                                <textarea class="textarea" name="content">{{ $settings->welcome_content }}</textarea>
                            </div>
                        </div>

                        <div class="field">
                            <div class="control">
                                <input type="submit" value="{{ __('app.save') }}">
                            </div>
                        </div>
                    </form>
                </div>

                <div id="tab-page-15">
                    <form method="POST" action="{{ url('/maintainer/formattedprojectname') }}">
                        @csrf

                        <div class="field">
                            <label class="label">{{ __('app.project_name_formatted_description') }}</label>
                            <div class="control">
                                <textarea class="textarea" name="code">{{ $settings->formatted_project_name }}</textarea>
                            </div>
                        </div>

                        <div class="field">
                            <div class="control">
                                <input type="submit" value="{{ __('app.save') }}">
                            </div>
                        </div>
                    </form>
                </div>

                <div id="tab-page-16">
                    <form method="POST" action="{{ url('/maintainer/save') }}">
                        @csrf

                        <input type="hidden" name="attribute" value="head_code">

                        <div class="field">
                            <label class="label">{{ __('app.head_code_description') }}</label>
                            <div class="control">
                                <textarea class="textarea" name="content">{{ $settings->head_code }}</textarea>
                            </div>
                        </div>

                        <div class="field">
                            <div class="control">
                                <input type="submit" value="{{ __('app.save') }}">
                            </div>
                        </div>
                    </form>
                </div>

                <div id="tab-page-17">
                    <form method="POST" action="{{ url('/maintainer/save') }}">
                        @csrf

                        <input type="hidden" name="attribute" value="adcode">

                        <div class="field">
                            <label class="label">{{ __('app.adcode_description') }}</label>
                            <div class="control">
                                <textarea class="textarea" name="content">{{ $settings->adcode }}</textarea>
                            </div>
                        </div>

                        <div class="field">
                            <div class="control">
                                <input type="submit" value="{{ __('app.save') }}">
                            </div>
                        </div>
                    </form>
                </div>

                <div id="tab-page-18">
                    <table class="table striped table-border mt-4" data-role="table" data-pagination="true"
                           data-table-rows-count-title="{{ __('app.table_show_entries') }}"
                           data-table-search-title="{{ __('app.table_search') }}"
                           data-table-info-title="{{ __('app.table_row_info') }}"
                           data-pagination-prev-title="{{ __('app.table_pagination_prev') }}"
                           data-pagination-next-title="{{ __('app.table_pagination_next') }}">
                        <thead>
                        <tr>
                            <th class="text-left">{{ __('app.profile_item_id') }}</th>
                            <th class="text-left">{{ __('app.profile_item_name') }}</th>
                            <th class="text-left">{{ __('app.profile_item_translation') }}</th>
                            <th class="text-left">{{ __('app.profile_item_locale') }}</th>
                            <th class="text-left">{{ __('app.profile_item_active') }}</th>
                            <th class="text-right">{{ __('app.remove') }}</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach (\App\ProfileModel::all() as $pi)
                            <tr>
                                <td>
                                    #{{ $pi->id }}
                                </td>

                                <td class="right">
                                    <a href="javascript:void(0)" onclick="document.getElementById('profile-item-id').value = {{ $pi->id }}; document.getElementById('profile-item-name').value = '{{ $pi->name }}'; document.getElementById('profile-item-translation').value = '{{ $pi->translation }}'; document.getElementById('profile-item-locale').value = '{{ $pi->locale }}'; document.getElementById('profile-item-active').checked = {{ ($pi->active) ? 'true' : 'false' }}; vue.bShowEditProfileItem = true;" title="{{ __('app.profile_item_edit') }}">{{ $pi->name }}</a>
                                </td>

                                <td>
                                    {{ $pi->translation }}
                                </td>

                                <td>{{ $pi->locale }}</td>

                                <td>{{ ($pi->active) ? __('app.active') : __('app.inactive') }}</td>

                                <td>
                                    <a href="javascript:void(0)" onclick="if (confirm('{{ __('app.profile_item_remove_confirm') }}')) location.href = '{{ url('/maintainer/profileitem/' . $pi->id . '/remove') }}';">{{ __('app.profile_item_remove') }}</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <br/>

                    <center><a class="button is-success" onclick="vue.bShowCreateProfileItem = true;">{{ __('app.create') }}</a></center><br/>
                </div>

                <div id="tab-page-19">
                    <table class="table striped table-border mt-4" data-role="table" data-pagination="true"
                           data-table-rows-count-title="{{ __('app.table_show_entries') }}"
                           data-table-search-title="{{ __('app.table_search') }}"
                           data-table-info-title="{{ __('app.table_row_info') }}"
                           data-pagination-prev-title="{{ __('app.table_pagination_prev') }}"
                           data-pagination-next-title="{{ __('app.table_pagination_next') }}">
                        <thead>
                        <tr>
                            <th class="text-left">{{ __('app.forum_id') }}</th>
                            <th class="text-left">{{ __('app.forum_name') }}</th>
                            <th class="text-left">{{ __('app.forum_lock') }}</th>
                            <th class="text-right">{{ __('app.remove') }}</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($forums as $forum)
                            <tr>
                                <td>
                                    #{{ $forum->id }}
                                </td>

                                <td class="right">
                                    <a href="javascript:void(0)" onclick="document.getElementById('forum-edit-id').value = {{ $forum->id }}; document.getElementById('forum-edit-name').value = '{{ $forum->name }}'; document.getElementById('forum-edit-description').value = '{{ $forum->description }}'; vue.bShowEditForum = true;" title="{{ __('app.forum_edit') }}">{{ $forum->name }}</a>
                                </td>

                                <td><a href="javascript:void(0);" onclick="location.href = '{{ url('/maintainer/forum/' . $forum->id . '/lock') }}';">{{ __('app.lock') }}</a></td>

                                <td>
                                    <a href="javascript:void(0)" onclick="if (confirm('{{ __('app.forum_remove_confirm') }}')) location.href = '{{ url('/maintainer/forum/' . $forum->id . '/remove') }}';">{{ __('app.forum_remove') }}</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <br/>

                    <center><a class="button" href="javascript:void(0)" onclick="location.reload();">{{ __('app.refresh') }}</a>&nbsp;&nbsp;&nbsp;&nbsp;
                    <a class="button is-success" onclick="vue.bShowCreateForum = true;">{{ __('app.create') }}</a></center><br/>
                </div>

                <div id="tab-page-20">
                    <table class="table striped table-border mt-4" data-role="table" data-pagination="true"
                           data-table-rows-count-title="{{ __('app.table_show_entries') }}"
                           data-table-search-title="{{ __('app.table_search') }}"
                           data-table-info-title="{{ __('app.table_row_info') }}"
                           data-pagination-prev-title="{{ __('app.table_pagination_prev') }}"
                           data-pagination-next-title="{{ __('app.table_pagination_next') }}">
                        <thead>
                        <tr>
                            <th class="text-left">{{ __('app.category_id') }}</th>
                            <th class="text-left">{{ __('app.category_name') }}</th>
                            <th class="text-left">{{ __('app.category_icon') }}</th>
                            <th class="text-left">{{ __('app.category_last_updated') }}</th>
                            <th class="text-right">{{ __('app.remove') }}</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($categories as $cat)
                            <tr>
                                <td>
                                    #{{ $cat->id }}
                                </td>

                                <td class="right">
                                    <a href="javascript:void(0)" onclick="document.getElementById('cat-id').value = {{ $cat->id }}; document.getElementById('cat-name').value = '{{ $cat->name }}'; document.getElementById('cat-icon').value = '{{ $cat->icon }}'; vue.bShowEditCat = true;" title="{{ __('app.cat_edit') }}">{{ $cat->name }}</a>
                                </td>

                                <td>
                                    {{ $cat->icon }}
                                </td>

                                <td><div title="{{ $cat->updated_at }}">{{ $cat->updated_at->diffForHumans() }}</div></td>

                                <td>
                                    <a href="javascript:void(0)" onclick="if (confirm('{{ __('app.cat_remove_confirm') }}')) location.href = '{{ url('/maintainer/category/' . $cat->id . '/remove') }}';">{{ __('app.cat_remove') }}</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <br/>

                    <center><a class="button" href="javascript:void(0)" onclick="location.reload();">{{ __('app.refresh') }}</a>&nbsp;&nbsp;&nbsp;&nbsp;
                    <a class="button is-success" onclick="vue.bShowCreateCat = true;">{{ __('app.create') }}</a></center><br/>
                </div>
            </div>

            <div class="modal" :class="{'is-active': bShowCreateFaq}">
                <div class="modal-background"></div>
                <div class="modal-card">
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
                <div class="modal-card">
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

            <div class="modal" :class="{'is-active': bShowCreateCat}">
                <div class="modal-background"></div>
                <div class="modal-card">
                    <header class="modal-card-head is-stretched">
                        <p class="modal-card-title">{{ __('app.category_create') }}</p>
                        <button class="delete" aria-label="close" onclick="vue.bShowCreateCat = false;"></button>
                    </header>
                    <section class="modal-card-body is-stretched">
                        <form method="POST" action="{{ url('/maintainer/category/create') }}" id="formCreateCat">
                            @csrf

                            <div class="field is-stretched">
                                <label class="label">{{ __('app.category_name') }}</label>
                                <div class="control">
                                    <input type="text" name="name">
                                </div>
                            </div>

                            <div class="field is-stretched">
                                <label class="label">{{ __('app.category_icon') }}</label>
                                <div class="control">
                                    <input type="text" name="icon">
                                </div>
                            </div>
                        </form>
                    </section>
                    <footer class="modal-card-foot is-stretched">
                        <button class="button is-success" onclick="document.getElementById('formCreateCat').submit();">{{ __('app.save') }}</button>
                        <button class="button" onclick="vue.bShowCreateCat = false;">{{ __('app.cancel') }}</button>
                    </footer>
                </div>
            </div>

            <div class="modal" :class="{'is-active': bShowEditCat}">
                <div class="modal-background"></div>
                <div class="modal-card">
                    <header class="modal-card-head is-stretched">
                        <p class="modal-card-title">{{ __('app.category_edit') }}</p>
                        <button class="delete" aria-label="close" onclick="vue.bShowEditCat = false;"></button>
                    </header>
                    <section class="modal-card-body is-stretched">
                        <form method="POST" action="{{ url('/maintainer/category/edit') }}" id="formEditCat">
                            @csrf

                            <input type="hidden" name="id" id="cat-id">

                            <div class="field is-stretched">
                                <label class="label">{{ __('app.category_name') }}</label>
                                <div class="control">
                                    <input type="text" name="name" id="cat-name">
                                </div>
                            </div>

                            <div class="field is-stretched">
                                <label class="label">{{ __('app.category_icon') }}</label>
                                <div class="control">
                                    <input type="text" name="icon" id="cat-icon">
                                </div>
                            </div>
                        </form>
                    </section>
                    <footer class="modal-card-foot is-stretched">
                        <button class="button is-success" onclick="document.getElementById('formEditCat').submit();">{{ __('app.save') }}</button>
                        <button class="button" onclick="vue.bShowEditCat = false;">{{ __('app.cancel') }}</button>
                    </footer>
                </div>
            </div>

            <div class="modal" :class="{'is-active': bShowCreateTheme}">
                <div class="modal-background"></div>
                <div class="modal-card">
                    <header class="modal-card-head is-stretched">
                        <p class="modal-card-title">{{ __('app.edit_theme') }}</p>
                        <button class="delete" aria-label="close" onclick="vue.bShowCreateTheme = false;"></button>
                    </header>
                    <section class="modal-card-body is-stretched">
                        <form method="POST" action="{{ url('/maintainer/themes/add') }}" id="formCreateTheme">
                            @csrf

                            <div class="field is-stretched">
                                <label class="label">{{ __('app.theme_name') }}</label>
                                <div class="control">
                                    <input type="text" name="name">
                                </div>
                            </div>

                            <div class="field is-stretched">
                                <label class="label">{{ __('app.theme_content') }}</label>
                                <div class="control">
                                    <textarea name="code"></textarea>
                                </div>
                            </div>
                        </form>
                    </section>
                    <footer class="modal-card-foot is-stretched">
                        <button class="button is-success" onclick="document.getElementById('formCreateTheme').submit();">{{ __('app.save') }}</button>
                        <button class="button" onclick="vue.bShowCreateTheme = false;">{{ __('app.cancel') }}</button>
                    </footer>
                </div>
            </div>

            <div class="modal" :class="{'is-active': bShowEditTheme}">
                <div class="modal-background"></div>
                <div class="modal-card">
                    <header class="modal-card-head is-stretched">
                        <p class="modal-card-title">{{ __('app.edit_faq') }}</p>
                        <button class="delete" aria-label="close" onclick="vue.bShowEditTheme = false;"></button>
                    </header>
                    <section class="modal-card-body is-stretched">
                        <form method="POST" action="{{ url('/maintainer/themes/edit') }}" id="formEditTheme">
                            @csrf

                            <input type="hidden" name="name" id="theme_name">

                            <div class="field is-stretched">
                                <label class="label">{{ __('app.theme_content') }}</label>
                                <div class="control">
                                    <textarea name="code" id="theme_code"></textarea>
                                </div>
                            </div>
                        </form>
                    </section>
                    <footer class="modal-card-foot is-stretched">
                        <button class="button is-success" onclick="document.getElementById('formEditTheme').submit();">{{ __('app.save') }}</button>
                        <button class="button" onclick="vue.bShowEditTheme = false;">{{ __('app.cancel') }}</button>
                    </footer>
                </div>
            </div>

            <div class="modal" :class="{'is-active': bShowCreateProfileItem}">
                <div class="modal-background"></div>
                <div class="modal-card">
                    <header class="modal-card-head is-stretched">
                        <p class="modal-card-title">{{ __('app.profile_item_create') }}</p>
                        <button class="delete" aria-label="close" onclick="vue.bShowCreateProfileItem = false;"></button>
                    </header>
                    <section class="modal-card-body is-stretched">
                        <form method="POST" action="{{ url('/maintainer/profileitem/create') }}" id="formCreateProfileItem">
                            @csrf

                            <div class="field is-stretched">
                                <label class="label">{{ __('app.profile_item_name') }}</label>
                                <div class="control">
                                    <input type="text" name="name">
                                </div>
                            </div>

                            <div class="field is-stretched">
                                <label class="label">{{ __('app.profile_item_translation') }}</label>
                                <div class="control">
                                    <input type="text" name="translation">
                                </div>
                            </div>

                            <div class="field is-stretched">
                                <label class="label">{{ __('app.profile_item_locale') }}</label>
                                <div class="control">
                                    <input type="text" name="locale" value="{{ \App::getLocale() }}">
                                </div>
                            </div>
                        </form>
                    </section>
                    <footer class="modal-card-foot is-stretched">
                        <button class="button is-success" onclick="document.getElementById('formCreateProfileItem').submit();">{{ __('app.save') }}</button>
                        <button class="button" onclick="vue.bShowCreateProfileItem = false;">{{ __('app.cancel') }}</button>
                    </footer>
                </div>
            </div>

            <div class="modal" :class="{'is-active': bShowEditProfileItem}">
                <div class="modal-background"></div>
                <div class="modal-card">
                    <header class="modal-card-head is-stretched">
                        <p class="modal-card-title">{{ __('app.edit_faq') }}</p>
                        <button class="delete" aria-label="close" onclick="vue.bShowEditProfileItem = false;"></button>
                    </header>
                    <section class="modal-card-body is-stretched">
                        <form method="POST" action="{{ url('/maintainer/profileitem/edit') }}" id="formEditProfileItem">
                            @csrf

                            <input type="hidden" name="id" id="profile-item-id">

                            <div class="field is-stretched">
                                <label class="label">{{ __('app.profile_item_name') }}</label>
                                <div class="control">
                                    <input type="text" name="name" id="profile-item-name">
                                </div>
                            </div>

                            <div class="field is-stretched">
                                <label class="label">{{ __('app.profile_item_translation') }}</label>
                                <div class="control">
                                    <input type="text" name="translation" id="profile-item-translation">
                                </div>
                            </div>

                            <div class="field is-stretched">
                                <label class="label">{{ __('app.profile_item_locale') }}</label>
                                <div class="control">
                                    <input type="text" name="locale" id="profile-item-locale">
                                </div>
                            </div>

                            <div class="field is-stretched">
                                <input id="profile-item-active" type="checkbox" name="active" data-role="checkbox" data-style="2" data-caption="{{ __('app.profile_item_active') }}" value="1">
                            </div>
                        </form>
                    </section>
                    <footer class="modal-card-foot is-stretched">
                        <button class="button is-success" onclick="document.getElementById('formEditProfileItem').submit();">{{ __('app.save') }}</button>
                        <button class="button" onclick="vue.bShowEditProfileItem = false;">{{ __('app.cancel') }}</button>
                    </footer>
                </div>
            </div>

            <div class="modal" :class="{'is-active': bShowCreateForum}">
                <div class="modal-background"></div>
                <div class="modal-card">
                    <header class="modal-card-head is-stretched">
                        <p class="modal-card-title">{{ __('app.forum_create') }}</p>
                        <button class="delete" aria-label="close" onclick="vue.bShowCreateForum = false;"></button>
                    </header>
                    <section class="modal-card-body is-stretched">
                        <form method="POST" action="{{ url('/maintainer/forum/create') }}" id="formCreateForum">
                            @csrf

                            <div class="field is-stretched">
                                <label class="label">{{ __('app.forum_name') }}</label>
                                <div class="control">
                                    <input type="text" name="name">
                                </div>
                            </div>

                            <div class="field is-stretched">
                                <label class="label">{{ __('app.forum_description') }}</label>
                                <div class="control">
                                    <textarea name="description"></textarea>
                                </div>
                            </div>
                        </form>
                    </section>
                    <footer class="modal-card-foot is-stretched">
                        <button class="button is-success" onclick="document.getElementById('formCreateForum').submit();">{{ __('app.create') }}</button>
                        <button class="button" onclick="vue.bShowCreateForum = false;">{{ __('app.cancel') }}</button>
                    </footer>
                </div>
            </div>

            <div class="modal" :class="{'is-active': bShowEditForum}">
                <div class="modal-background"></div>
                <div class="modal-card">
                    <header class="modal-card-head is-stretched">
                        <p class="modal-card-title">{{ __('app.forum_edit') }}</p>
                        <button class="delete" aria-label="close" onclick="vue.bShowEditForum = false;"></button>
                    </header>
                    <section class="modal-card-body is-stretched">
                        <form method="POST" action="{{ url('/maintainer/forum/edit') }}" id="formEditForum">
                            @csrf

                            <input type="hidden" name="id" id="forum-edit-id">

                            <div class="field is-stretched">
                                <label class="label">{{ __('app.forum_name') }}</label>
                                <div class="control">
                                    <input type="text" name="name" id="forum-edit-name">
                                </div>
                            </div>

                            <div class="field is-stretched">
                                <label class="label">{{ __('app.forum_description') }}</label>
                                <div class="control">
                                    <textarea name="description" id="forum-edit-description"></textarea>
                                </div>
                            </div>
                        </form>
                    </section>
                    <footer class="modal-card-foot is-stretched">
                        <button class="button is-success" onclick="document.getElementById('formEditForum').submit();">{{ __('app.save') }}</button>
                        <button class="button" onclick="vue.bShowEditForum = false;">{{ __('app.cancel') }}</button>
                    </footer>
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
                    document.getElementById('user_maintainer').checked = response.data.maintainer;
                } else {
                    document.getElementById('user_settings').classList.add('is-hidden');
                    alert(response.msg);
                }
            });
        }
    </script>
@endsection

{{--
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2021 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

<div class="column is-5">
    <div class="stories-frame" id="stories">
        <div class="stories-item is-pointer" onclick="window.vue.bShowAddStory = true; window.clearStoryInput();">
            <div class="stories-item-avatar">
                <i class="fas fa-plus is-color-grey stories-add-icon"></i>
            </div>
            <div class="stories-item-username">
                {{ __('app.add_story') }}
            </div>
        </div>
    </div>
</div>

<div class="column is-5 is-sidespacing"></div>

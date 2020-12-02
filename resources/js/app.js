/*
    Danigram (dnyDanigram) developed by Daniel Brendel

        (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
*/

const MAX_SHARE_TEXT_LENGTH = 15;

//Make Vue instance
let vue = new Vue({
    el: '#main',

    data: {
        bShowRecover: false,
        bShowRegister: false,
        bShowEditProfile: false,
        bShowEditComment: false,
        bShowCreateFaq: false,
        bShowEditFaq: false,
        bShowLogin: false,
        bShowWelcomeOverlay: false,
        bShowCreateTheme: false,
        bShowEditTheme: false,
        bShowReplyThread: false,
        bShowViewStory: false,
        bShowAddStory: false,
        bShowBuyProMode: false,
        bShowCreateProfileItem: false,
        bShowEditProfileItem: false,
        translationTable: {
            copiedToClipboard: 'Text has been copyied to clipboard!',
            toggleNsfw: 'Toggle NSFW',
            toggleNsfw2: '[NSFW] Toggle',
            lock: 'Lock',
            edit: 'Edit',
            shareWhatsApp: 'Share via WhatsApp',
            shareTwitter: 'Share via Twitter',
            shareFacebook: 'Share via Facebook',
            shareEMail: 'Share via E-Mail',
            shareSms: 'Share via SMS',
            copyLink: 'Copy link',
            report: 'Report',
            expandThread: 'Expand thread',
            reply: 'Reply',
            viewMore: 'View more',
            reportPost: 'The post has been reported!',
            removeFav: 'Remove favorite',
            addFav: 'Add favorite',
            noFavsYet: 'You don\'t have set any favorites yet',
            confirmLockPost: 'Do you want to lock this post?',
            confirmToggleNsfw: 'Do you want to toggle the nsfw flag for this post?',
            confirmLockHashtag: 'Do you want to lock this hashtag?',
            confirmLockUser: 'Do you want to deactivate this profile?',
            confirmDeleteOwnAccount: 'Do you really want to delete your profile? If yes then please enter your password in order to proceed.',
            confirmLockComment: 'Do you want to lock this comment?',
            pro: 'Pro'
        }
    },

    methods: {
        invalidLoginEmail: function () {
            var el = document.getElementById("loginemail");

            if ((el.value.length == 0) || (el.value.indexOf('@') == -1) || (el.value.indexOf('.') == -1)) {
                el.classList.add('is-danger');
            } else {
                el.classList.remove('is-danger');
            }
        },

        invalidRecoverEmail: function () {
            var el = document.getElementById("recoveremail");

            if ((el.value.length == 0) || (el.value.indexOf('@') == -1) || (el.value.indexOf('.') == -1)) {
                el.classList.add('is-danger');
            } else {
                el.classList.remove('is-danger');
            }
        },

        invalidLoginPassword: function () {
            var el = document.getElementById("loginpw");

            if (el.value.length == 0) {
                el.classList.add('is-danger');
            } else {
                el.classList.remove('is-danger');
            }
        },

        handleCookieConsent: function () {
            //Show cookie consent if not already for this client

            let cookies = document.cookie.split(';');
            let foundCookie = false;
            for (let i = 0; i < cookies.length; i++) {
                if (cookies[i].indexOf('cookieconsent') !== -1) {
                    foundCookie = true;
                    break;
                }
            }

            if (foundCookie === false) {
                document.getElementById('cookie-consent').style.display = 'inline-block';
                document.getElementById('feed-left').classList.add('is-negative-top');
            }
        },

        clickedCookieConsentButton: function () {
            let expDate = new Date(Date.now() + 1000 * 60 * 60 * 24 * 365);
            document.cookie = 'cookieconsent=1; expires=' + expDate.toUTCString() + ';';

            document.getElementById('cookie-consent').style.display = 'none';

            document.getElementById('feed-left').classList.remove('is-negative-top');
        },

        markWelcomeOverlayRead: function() {
            let expDate = new Date(Date.now() + 1000 * 60 * 60 * 24 * 365);
            document.cookie = 'welcome_content=1; expires=' + expDate.toUTCString() + ';';
            this.bShowWelcomeOverlay = false;
        },

        handleWelcomeOverlay: function () {
            let cookies = document.cookie.split(';');
            for (let i = 0; i < cookies.length; i++) {
                if (cookies[i].indexOf('welcome_content') !== -1) {
                    this.bShowWelcomeOverlay = false;
                    return;
                }
            }

            this.bShowWelcomeOverlay = true;
        },

        setPostFetchType: function (type) {
            let expDate = new Date(Date.now() + 1000 * 60 * 60 * 24 * 365);
            document.cookie = 'fetch_type=' + type + '; expires=' + expDate.toUTCString() + ';';
        },

        getPostFetchType: function () {
            let cookies = document.cookie.split(';');

            for (let i = 0; i < cookies.length; i++) {
                if (cookies[i].indexOf('fetch_type') !== -1) {
                    return cookies[i].substr(cookies[i].indexOf('=') + 1);
                }
            }

            this.setPostFetchType(2);

            return 2;
        },

        ajaxRequest: function (method, url, data = {}, successfunc = function(data){}, finalfunc = function(){}, config = {})
        {
            //Perform ajax request

            let func = window.axios.get;
            if (method == 'post') {
                func = window.axios.post;
            } else if (method == 'patch') {
                func = window.axios.patch;
            } else if (method == 'delete') {
                func = window.axios.delete;
            }

            func(url, data, config)
                .then(function(response){
                    successfunc(response.data);
                })
                .catch(function (error) {
                    console.log(error);
                })
                .finally(function(){
                        finalfunc();
                }
            );
        },

        toggleHeart: function(elemId, type) {
            let obj = document.getElementById('heart-' + type.toLowerCase() + '-' + elemId);

            this.ajaxRequest('post', window.location.origin + '/heart', { entity: elemId, value: !parseInt(obj.getAttribute('data-value')), type: type}, function(response) {
               if (response.code === 200) {
                   if (response.value) {
                       obj.classList.remove('far', 'fa-heart');
                       obj.classList.add('fas', 'fa-heart', 'is-hearted');
                   } else {
                       obj.classList.remove('fas', 'fa-heart', 'is-hearted');
                       obj.classList.add('far', 'fa-heart');
                   }

                   obj.setAttribute('data-value', ((response.value) ? '1' : '0'));

                   document.getElementById('count-' + type.toLowerCase() + '-' + elemId).innerHTML = response.count;
               }
            });
        },

        togglePostOptions: function(elem) {
            if (elem.classList.contains('is-active')) {
                elem.classList.remove('is-active');
            } else {
                elem.classList.add('is-active');
            }
        },

        toggleCommentOptions: function(elem) {
            if (elem.classList.contains('is-active')) {
                elem.classList.remove('is-active');
            } else {
                elem.classList.add('is-active');
            }
        },

        copyToClipboard: function(text) {
            const el = document.createElement('textarea');
            el.value = text;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            alert(window.vue.translationTable.copiedToClipboard);
        },

        showError: function ()
        {
            document.getElementById('flash-error').style.display = 'inherit';
            setTimeout(function() { document.getElementById('flash-error').style.display = 'none'; }, 3500);
        },

        showSuccess: function()
        {
            document.getElementById('flash-success').style.display = 'inherit';
            setTimeout(function() { document.getElementById('flash-success').style.display = 'none'; }, 3500);
        }
    }
});

window.renderPost = function(elem, adminOrOwner = false, showNsfw = 0, nsfwFunctionalityEnabled = false)
{
    if (elem._type === 'ad') {
        let html = '<div class="show-post member-form is-advertisement">' + elem.code + '</div>';

        return html;
    }

    let hashTags = '';
    let hashArr = elem.hashtags.trim().split(' ');
    hashArr.forEach(function (elem, index) {
        if (elem.length > 0) {
            hashTags += '<a href="' + window.location.origin + '/t/' + elem + '">#' + elem + '</a>&nbsp;';
        }
    });

    let nsfwOption = `<a href="javascript:void(0)" onclick="toggleNsfw(` + elem.id + `); window.vue.togglePostOptions(document.getElementById('post-options-` + elem.id + `'));" class="dropdown-item">
                ` + window.vue.translationTable.toggleNsfw + `
            </a> `;

    let instagram = '';
    let twitter = '';
    let homepage = '';

    if (elem.attribution_instagram.length > 0) {
        instagram = '<div><a href="https://www.instagram.com/' + elem.attribution_instagram + '"><i class="fab fa-instagram"></i>&nbsp;' + elem.attribution_instagram + '</a>';
    }
    if (elem.attribution_twitter.length > 0) {
        twitter = '<div><a href="https://twitter.com/' + elem.attribution_twitter + '"><i class="fab fa-twitter"></i>&nbsp;' + elem.attribution_twitter + '</a>';
    }
    if (elem.attribution_homepage.length > 0) {
        if ((!elem.attribution_homepage.startsWith('http://')) && (!elem.attribution_homepage.startsWith('https://'))) {
            elem.attribution_homepage = 'http://' + elem.attribution_homepage;
        }

        homepage = '<div><a href="' + elem.attribution_homepage + '"><i class="fas fa-external-link-alt"></i>&nbsp;' + elem.attribution_homepage + '</a>';
    }

    let credits = '';
    if ((instagram.length > 0) || (twitter.length > 0) || (homepage.length > 0)) {
        credits = instagram + twitter + homepage;
    }

    let adminOptions = '';
    if (adminOrOwner) {
        adminOptions = `
            <a href="javascript:void(0)" onclick="lockPost(` + elem.id + `); window.vue.togglePostOptions(document.getElementById('post-options-` + elem.id + `'));" class="dropdown-item">
                ` + window.vue.translationTable.lock + `
            </a>
            ` + ((nsfwFunctionalityEnabled) ? nsfwOption : '');
    }

    let gfx_resource = '';
    if (elem.video) {
        gfx_resource = `<video id="post-image-` + elem.id + `" class="is-stretched ` + (((elem.nsfw) && (showNsfw === 0)) ? 'show-post-image-nsfw' : '') + `" controls><source src="` + window.location.origin + '/gfx/posts/' + elem.image_full + `"/></video>`;
    } else {
        gfx_resource = `<img id="post-image-` + elem.id + `" class="is-pointer is-stretched ` + (((elem.nsfw) && (showNsfw === 0)) ? 'show-post-image-nsfw' : '') + `" src="` + window.location.origin + `/gfx/posts/` + elem.image_thumb + `" onclick="location.href='` + window.location.origin + '/p/' + elem.id + `'">`;
    }

    let post_desc = '';

    if (elem.image_full === '_none') {
        gfx_resource = `<pre id="post-image-` + elem.id + `" class="show-post-description is-default-padding is-color-grey is-post-background is-pointer ` + (((elem.nsfw) && (showNsfw === 0)) ? 'show-post-image-nsfw' : '') + `" onclick="location.href='` + window.location.origin + '/p/' + elem.id + `'">` + elem.description + `</pre>`;

        post_desc = '';
    } else {
        post_desc = `<pre class="is-post-background">` + elem.description + `</pre>`;
    }

    let pro = '';
    if (elem.user.pro) {
        pro = '<i class="fas fa-certificate is-color-pro" title="' + window.vue.translationTable.pro + '"></i>'
    }

    let post_hashtags = '';
    if (hashTags.length > 0) {
        post_hashtags = `<div class="show-post-hashtags is-default-padding is-wordbreak">` + hashTags + `</div>`;
    } else {
        post_hashtags = '';
    }

    let post_credits = '';
    if (credits.length > 0) {
        post_credits = `<div class="show-post-credits is-default-padding">` + credits + `</div>`;
    } else {
        post_credits = '';
    }

    let html = `
                            <div class="show-post member-form">
                            <div class="show-post-header is-default-padding">
                                <div class="show-post-avatar">
                                    <img src="` + window.location.origin + '/gfx/avatars/' + elem.user.avatar + `" class="is-pointer" onclick="location.href='` + window.location.origin + `/u/` + elem.user.username + `'" width="32" height="32">
                                </div>

                                <div class="show-post-userinfo">
                                    <div><a href="` + window.location.origin + `/u/` + elem.user.username + `" class="is-color-grey">` + elem.user.username + `</a>&nbsp;` + pro + `</div>
                                    <div title="` + elem.created_at + `">` + elem.diffForHumans + `</div>
                                </div>

                                <div class="show-post-options is-inline-block">
                                    <div class="dropdown is-right" id="post-options-` + elem.id + `">
                                        <div class="dropdown-trigger" onclick="window.vue.togglePostOptions(document.getElementById('post-options-` + elem.id + `'));">
                                            <i class="fas fa-ellipsis-v is-pointer"></i>
                                        </div>
                                        <div class="dropdown-menu" role="menu">
                                            <div class="dropdown-content">
                                                <a onclick="window.vue.togglePostOptions(document.getElementById('post-options-` + elem.id + `'));" href="whatsapp://send?text=` + window.location.origin + `/p/` + elem.id + ` ` + ((elem.description.length > MAX_SHARE_TEXT_LENGTH) ? elem.description.substr(0, MAX_SHARE_TEXT_LENGTH) + '...' : elem.description) + `" class="dropdown-item">
                                                    <i class="far fa-copy"></i>&nbsp;` + window.vue.translationTable.shareWhatsApp + `
                                                </a>
                                                <a onclick="window.vue.togglePostOptions(document.getElementById('post-options-` + elem.id + `'));" href="https://twitter.com/share?url=` + encodeURIComponent(window.location.origin + '/p/' + elem.id) + `&text=` + ((elem.description.length > MAX_SHARE_TEXT_LENGTH) ? elem.description.substr(0, MAX_SHARE_TEXT_LENGTH) + '...' : elem.description) + `" class="dropdown-item">
                                                    <i class="fab fa-twitter"></i>&nbsp;` + window.vue.translationTable.shareTwitter + `
                                                </a>
                                                <a onclick="window.vue.togglePostOptions(document.getElementById('post-options-` + elem.id + `'));" href="https://www.facebook.com/sharer/sharer.php?u=` + window.location.origin + `/p/` + elem.id + `" class="dropdown-item">
                                                    <i class="fab fa-facebook"></i>&nbsp;` + window.vue.translationTable.shareFacebook + `
                                                </a>
                                                <a onclick="window.vue.togglePostOptions(document.getElementById('post-options-` + elem.id + `'));" href="mailto:name@domain.com?body=` + window.location.origin + `/p/` + elem.id + ` ` + ((elem.description.length > MAX_SHARE_TEXT_LENGTH) ? elem.description.substr(0, MAX_SHARE_TEXT_LENGTH) + '...' : elem.description) + `" class="dropdown-item">
                                                    <i class="far fa-envelope"></i>&nbsp;` + window.vue.translationTable.shareEMail + `
                                                </a>
                                                <a onclick="window.vue.togglePostOptions(document.getElementById('post-options-` + elem.id + `'));" href="sms:000000000?body=` + window.location.origin + `/p/` + elem.id + ` ` + ((elem.description.length > MAX_SHARE_TEXT_LENGTH) ? elem.description.substr(0, MAX_SHARE_TEXT_LENGTH) + '...' : elem.description) + `" class="dropdown-item">
                                                    <i class="fas fa-sms"></i>&nbsp;` + window.vue.translationTable.shareSms + `
                                                </a>
                                                <a href="javascript:void(0)" onclick="window.vue.copyToClipboard('` + window.location.origin + `/p/` + elem.id + ` ` + ((elem.description.length > MAX_SHARE_TEXT_LENGTH) ? elem.description.substr(0, MAX_SHARE_TEXT_LENGTH) + '...' : elem.description) + `'); window.vue.togglePostOptions(document.getElementById('post-options-` + elem.id + `'));" class="dropdown-item">
                                                    <i class="far fa-copy"></i>&nbsp;` + window.vue.translationTable.copyLink + `
                                                </a>
                                                <hr class="dropdown-divider">
                                                <a href="javascript:void(0)" onclick="reportPost(` + elem.id + `); window.vue.togglePostOptions(document.getElementById('post-options-` + elem.id + `'));" class="dropdown-item">
                                                    ` + window.vue.translationTable.report + `
                                                </a>
                                                ` + adminOptions + `
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="show-post-image">
                                ` + gfx_resource + `
                            </div>

                            <div class="show-post-attributes is-default-padding-left is-default-padding-right">
                                <div class="is-inline-block"><span onclick="window.vue.toggleHeart(` + elem.id + `, 'ENT_POST')"><i id="heart-ent_post-` + elem.id + `" class="` + ((elem.userHearted) ? 'fas fa-heart is-hearted': 'far fa-heart') + ` is-pointer" data-value="` + ((elem.userHearted) ? '1' : '0') + `"></i></span> <span id="count-ent_post-` + elem.id + `">` + elem.hearts + `</span></div>
                                <div class="is-inline-block is-center-width ` + (((elem.nsfw) && (showNsfw === 0)) ? '' : 'is-hidden') + `"><center><a href="javascript:void(0)" onclick="let oPostImage = document.getElementById('post-image-` + elem.id + `'); if (oPostImage.classList.contains('show-post-image-nsfw')) { oPostImage.classList.remove('show-post-image-nsfw'); } else { oPostImage.classList.add('show-post-image-nsfw'); }" class="is-color-grey">` + window.vue.translationTable.toggleNsfw2 + `</a></center></div>
                                <div class="is-inline-block is-right float-right"><a class="is-color-grey" href="` + window.location.origin + `/p/` + elem.id + `#thread">` + elem.comment_count + ` comments</a></div>
                            </div>

                            <div class="show-post-description is-default-padding is-color-grey">
                                ` + post_desc + `
                                       </div>

                                       ` + post_hashtags + `

                                       ` + post_credits + `
                                   </div>
                        `;
    return html;
};

window.renderThread = function(elem, adminOrOwner = false, isSubComment = false, parentId = 0) {
    let options = '';

    if (adminOrOwner) {
        options = `
            <a onclick="showEditComment(` + elem.id + `); window.vue.toggleCommentOptions(document.getElementById('thread-options-` + elem.id + `'));" href="javascript:void(0)" class="dropdown-item">
                <i class="far fa-edit"></i>&nbsp;` + window.vue.translationTable.edit + `
            </a>
            <a onclick="lockComment(` + elem.id + `); window.vue.toggleCommentOptions(document.getElementById('thread-options-` + elem.id + `'));" href="javascript:void(0)" class="dropdown-item">
                <i class="fas fa-times"></i>&nbsp;` + window.vue.translationTable.lock + `
            </a>
            <hr class="dropdown-divider">
        `;
    }

    let expandThread = '';
    if (elem.subCount > 0) {
        expandThread = `<div class="thread-footer-subthread is-inline-block is-centered"><a class="is-color-grey" href="javascript:void(0)" onclick="fetchSubThreadPosts(` + elem.id + `)">` + window.vue.translationTable.expandThread + `</a></div>`;
    }

    let replyThread = `<div class="is-inline-block float-right"><a class="is-color-grey" href="javascript:void(0)" onclick="document.getElementById('thread-reply-parent').value = '` + ((isSubComment) ? parentId : elem.id) + `'; document.getElementById('thread-reply-textarea').value = '@` + elem.user.username + ` '; window.vue.bShowReplyThread = true;">` + window.vue.translationTable.reply + `</a></div>`;

    let pro = '';
    if (elem.user.pro) {
        pro = '<i class="fas fa-certificate is-color-pro" title="' + window.vue.translationTable.pro + '"></i>'
    }

    let html = `
        <div id="thread-` + elem.id + `" class="thread-elem ` + ((isSubComment) ? 'is-sub-comment': '') + `">
            <a name="` + elem.id + `"></a>

            <div class="thread-header">
                <div class="thread-header-avatar is-inline-block">
                    <img width="24" height="24" src="` + window.location.origin + `/gfx/avatars/` + elem.user.avatar + `" class="is-pointer" onclick="location.href = '` + window.location.origin + `/u/` + elem.user.username + `';">
                </div>

                <div class="thread-header-info is-inline-block">
                    <div><a href="` + window.location.origin + `/u/` + elem.user.username + `" class="is-color-grey">` + elem.user.username + `</a>&nbsp;` + pro + `</div>
                    <div title="` + elem.created_at + `">` + elem.diffForHumans + `</div>
                </div>

                <div class="thread-header-options is-inline-block">
                    <div class="dropdown is-right" id="thread-options-` + elem.id + `">
                        <div class="dropdown-trigger" onclick="window.vue.togglePostOptions(document.getElementById('thread-options-` + elem.id + `'));">
                            <i class="fas fa-ellipsis-v is-pointer"></i>
                        </div>
                        <div class="dropdown-menu" role="menu">
                            <div class="dropdown-content">
                                ` + options + `

                                <a href="javascript:void(0)" onclick="reportComment(` + elem.id + `); window.vue.togglePostOptions(document.getElementById('thread-options-` + elem.id + `'));" class="dropdown-item">
                                    ` + window.vue.translationTable.report + `
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="thread-text is-color-grey" id="thread-text-` + elem.id + `">
                <pre class="is-post-background">` + elem.text + `</pre>
            </div>

            <div class="thread-footer">
                <div class="thread-footer-hearts"><i id="heart-ent_comment-` + elem.id + `" class="` + ((elem.userHearted) ? 'fas fa-heart is-hearted': 'far fa-heart') + ` is-pointer" onclick="window.vue.toggleHeart(` + elem.id + `, 'ENT_COMMENT')"></i>&nbsp;<span id="count-ent_comment-` + elem.id + `">` + elem.hearts + `</span></div>
                ` + expandThread + `
                ` + replyThread + `
            </div>

            <div id="sub-thread-` + elem.id + `"></div>
        </div>
    `;

    return html;
};

window.fetchSubThreadPosts = function(parentId) {
    if (typeof window.subPosts === 'undefined') {
        window.subPosts = [];
    }

    if (typeof window.subPosts[parentId] === 'undefined') {
        window.subPosts[parentId] = null;
    }

    document.getElementById('sub-thread-' + parentId).innerHTML += '<center><i class="fas fa-spinner fa-spin" id="spinner-sub-thread-' + parentId + '"></i></center>';

    window.vue.ajaxRequest('get', window.location.origin + '/c/subthread?parent=' + parentId + ((window.subPosts[parentId] !== null) ? '&paginate=' + window.subPosts[parentId] : ''), {}, function(response){
        if (response.code == 200) {
            document.getElementById('spinner-sub-thread-' + parentId).remove();

            let html = '';
            console.log(response.data);
            response.data.forEach(function(elem, index) {
                html += window.renderThread(elem, elem.adminOrOwner, true, parentId)
            });

            document.getElementById('sub-thread-' + parentId).innerHTML += html;

            if (response.last === false) {
                if (document.getElementById('sub-comment-more-' + parentId) !== null) {
                    document.getElementById('sub-comment-more-' + parentId).remove();
                }

                document.getElementById('sub-thread-' + parentId).innerHTML += `<center><div id="sub-comment-more-` + parentId + `"><a href="javascript:void(0)" onclick="fetchSubThreadPosts(` + parentId + `)">` + window.vue.translationTable.viewMore + `</a></div></center>`;
            }

            if (response.data.length === 0) {
                if (document.getElementById('sub-comment-more-' + parentId) !== null) {
                    document.getElementById('sub-comment-more-' + parentId).remove();
                }
            } else {
                window.subPosts[parentId] = response.data[response.data.length - 1].id;
            }
        }
    });
};

window.replyThread = function(parentId, text){
  window.vue.ajaxRequest('post', window.location.origin + '/c/reply?parent=' + parentId, { text: text }, function(response){
      if (response.code === 200) {
          location.href = window.location.origin + '/p/' + response.post.postId + '?c=' + response.post.id + '#' + response.post.id;
      }
  });
};

window.renderNotification = function(elem, newItem = false) {
    let icon = 'fas fa-info-circle';
    if (elem.type === 'PUSH_HEARTED') {
        icon = 'far fa-heart';
    } else if (elem.type === 'PUSH_COMMENTED') {
        icon = 'far fa-comment';
    } else if (elem.type === 'PUSH_MENTIONED') {
        icon = 'fas fa-bolt';
    } else if (elem.type === 'PUSH_MESSAGED') {
        icon = 'far fa-comments';
    } else if (elem.type === 'PUSH_FAVORITED') {
        icon = 'far fa-star';
    }

    let html = `
        <div class="notification-item ` + ((newItem) ? 'is-new-notification' : '') + `">
            <div class="notification-item-icon"><i class="` + icon + `"></i></div>
            <div class="notification-item-message">` + elem.longMsg + `</div>
        </div>
    `;

    return html;
};

window.renderMessageListItem = function(item) {
    let html = `
        <div class="messages-item ` + ((!item.seen) ? 'is-new-message' : '') + `">
            <div class="messages-item-avatar">
                <img src="` + window.location.origin + `/gfx/avatars/` + item.user.avatar + `">
            </div>

            <div class="messages-item-name">
                <a href="` + window.location.origin + `/u/` + item.user.username + `">` + item.user.username + `</a>
            </div>

            <div class="messages-item-subject">
                <a href="` + window.location.origin + `/messages/show/` + item.id + `">` + item.subject + `</a>
            </div>

            <div class="messages-item-date" title="` + item.created_at + `">
                ` + item.diffForHumans + `
            </div>
        </div>
    `;

    return html;
};

window.renderUserItem = function(item) {
    let html = `
    <div class="geo-user">
        <div class="geo-user-avatar">
            <a href="` + window.location.origin + `/u/` + item.id + `"><img src="` + window.location.origin + `/gfx/avatars/` + item.avatar + `" alt="avatar"/></a>
        </div>

        <div class="geo-user-info">
            <div class="geo-user-info-name"><a href="` + window.location.origin + `/u/` + item.id + `">` + item.username + `</a></div>
            <div class="geo-user-info-distance">~` + Math.round(item.distance) + ` KM</div>
        </div>
    </div>
    `;

    return html;
};

window.reportPost = function(id) {
  window.vue.ajaxRequest('post', window.location.origin + '/p/' + id + '/report', {}, function(response) {
    if (response.code === 200) {
        alert(window.vue.translationTable.postReported);
    }
  });
};

window.showEditComment = function(elemId) {
    document.getElementById('editCommentId').value = elemId;
    document.getElementById('editCommentText').value = document.getElementById('thread-text-' + elemId).innerHTML;
    window.vue.bShowEditComment = true;
};

window.editComment = function(elemId, text) {
    let oldContent = document.getElementById('thread-text-' + elemId).innerHTML;
    document.getElementById('thread-text-' + elemId).innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    window.vue.ajaxRequest('post', window.location.origin + '/comment/edit', { comment: elemId, text: text}, function (response) {
      if (response.code === 200) {
          document.getElementById('thread-text-' + elemId).innerHTML = text;
      } else {
          document.getElementById('thread-text-' + elemId).innerHTML = oldContent;
              alert(response.msg);
      }
    });
};

window.deleteComment = function(elemId) {
  if (confirm('Do you really want to delete this comment?')) {
      window.vue.ajaxRequest('post', window.location.origin + '/comment/delete', { comment: elemId }, function(response) {
          if (response.code === 200) {
              document.getElementById('thread-' + elemId).remove();
          } else {
              alert(response.msg);
          }
      });
  }
};

window.reportComment = function(elemId) {
    window.vue.ajaxRequest('post', window.location.origin + '/comment/report', { comment: elemId }, function(response) {
        alert(response.msg);
    });
};

window.reportProfile = function(elemId) {
    window.vue.ajaxRequest('get', window.location.origin + '/u/' + elemId + '/report', {}, function(response) {
        alert(response.msg);
    });
};

window.reportTag = function(elemId) {
    window.vue.ajaxRequest('get', window.location.origin + '/t/' + elemId + '/report', {}, function(response) {
        alert(response.msg);
    });
};

window.addFavorite = function(entityId, type, entityName = '') {
  window.vue.ajaxRequest('post', window.location.origin + '/f/add', { entityId: entityId, entType: type}, function(response) {
      if (response.code === 200) {
          let elems = document.getElementsByClassName('favorite-' + type.toLowerCase());
          for (let i = 0; i < elems.length; i++) {
              elems[i].innerHTML = '<a href="javascript:void(0)" onclick="removeFavorite(' + entityId + ', \'' + type + '\', \'' + entityName + '\')">' + window.vue.translationTable.removeFav + '</a>';
          }

          let link = '';
          if (type === 'ENT_HASHTAG') {
              link = '<a href="' + window.location.origin + '/t/' + entityName + '">#' + response.fav.short_name + '</a>';
          } else if (type === 'ENT_USER') {
              link = '<a href="' + window.location.origin + '/u/' + entityName + '">@' + response.fav.short_name + '</a>';
          }

          let avatar = '';
          if (type === 'ENT_HASHTAG') {
              if (response.avatar !== null) {
                  avatar = '<img src = "' + window.location.origin + '/gfx/posts/' + response.fav.avatar + '" width = "32" height = "32"/>';
              } else {
                  avatar = '&nbsp;<i class="fas fa-hashtag fa-lg" ></i>&nbsp;&nbsp;';
              }
          } else if (type === 'ENT_USER') {
            avatar = '<img src = "' + window.location.origin + '/gfx/avatars/' + response.fav.avatar + '" width = "32" height = "32"/>';
          }

          let html = `
            <div class="favorites-item is-block favorite-item-` + type.toLowerCase() + `-` + entityId + `">
                <div class="favorites-item-left is-inline-block">
                    <div class="favorites-item-left-avatar">
                        ` + avatar + `
                    </div>

                    <div class="favorites-item-left-info">
                        <div class="">
                            ` + link + `
                        </div>

                        <div class="is-color-grey">
                            ` + response.fav.total_posts + ` total posts
                        </div>
                    </div>
                </div>

                <div class="favorites-item-right is-inline-block"><i onclick="deleteFavorite(` + response.fav.id + `, ` + entityId + `, '` + type + `')" class="fas fa-times is-pointer" title="Remove"></i></div>
            </div>
          `;

          elems = document.getElementsByClassName('favorites-list');
          for (let i = 0; i < elems.length; i++) {
              elems[i].innerHTML += html;
          }

          elems = document.getElementsByClassName('has-no-favorites-yet');
          while (elems.length > 0) {
              elems[0].remove();
          }
      }
  });
};

window.removeFavorite = function(entityId, type, entityName = '') {
    window.vue.ajaxRequest('post', window.location.origin + '/f/remove', { entityId: entityId, entType: type}, function(response) {
        if (response.code === 200) {
            let elems = document.getElementsByClassName('favorite-' + type.toLowerCase());
            for (let i = 0; i < elems.length; i++) {
                elems[i].innerHTML = '<a href="javascript:void(0)" onclick="addFavorite(' + entityId + ', \'' + type + '\', \'' + entityName + '\')">' + window.vue.translationTable.addFav + '</a>';
            }

            elems = document.getElementsByClassName('favorite-item-' + type.toLowerCase() + '-' + entityId);
            while (elems.length > 0) {
                elems[0].remove();
            }

            elems = document.getElementsByClassName('favorites-item');
            if (elems.length === 0) {
                elems = document.getElementsByClassName('favorites-list');
                for (let i = 0; i < elems.length; i++) {
                    elems[i].innerHTML += '<i class="has-no-favorites-yet">' + window.vue.translationTable.noFavsYet + '</i>';
                }
            }
        }
    });
};

window.deleteFavorite = function(id, eid, type) {
    window.vue.ajaxRequest('post', window.location.origin + '/f/remove', { entityId: eid, entType: type }, function(response){
        if (response.code === 200) {
            let elems = document.getElementsByClassName('favorite-item-' + type.toLowerCase() + '-' + eid);
            while (elems.length > 0) {
                elems[0].remove();
            }

            elems = document.getElementsByClassName('favorites-item');
            if (elems.length === 0) {
                elems = document.getElementsByClassName('favorites-list');
                for (let i = 0; i < elems.length; i++) {
                    elems[i].innerHTML += '<i class="has-no-favorites-yet">You don\'t have set any favorites yet</i>';
                }
            }
        }
    });
}

window.clearPushIndicator = function(obj1, obj2) {
    if (obj1.classList.contains('is-hearted')) {
        obj1.classList.remove('fas', 'is-hearted');
        obj1.classList.add('far');
        obj1.setAttribute('title', 'Notifications');
    }

    obj2.style.display = 'none';
};

window.toggleNotifications = function(ident) {
    let obj = document.getElementById(ident);
    if (obj) {
        if (obj.style.display === 'block') {
            obj.style.display = 'none';
        } else {
            obj.style.display = 'block';
        }
    }
}

window.lockPost = function (id) {
    if (confirm(window.vue.translationTable.confirmLockPost)) {
        window.vue.ajaxRequest('get', window.location.origin + '/p/' + id + '/lock', {}, function (response) {
            alert(response.msg);
        });
    }
};

window.toggleNsfw = function (id) {
    if (confirm(window.vue.translationTable.confirmToggleNsfw)) {
        window.vue.ajaxRequest('get', window.location.origin + '/p/' + id + '/togglensfw', {}, function (response) {
            alert(response.msg);
        });
    }
}

window.lockHashtag = function (id) {
    if (confirm(window.vue.translationTable.confirmLockHashtag)) {
        window.vue.ajaxRequest('get', window.location.origin + '/t/' + id + '/lock', {}, function (response) {
            alert(response.msg);
        });
    }
};

window.lockUser = function (id, self = false) {
    if (confirm(window.vue.translationTable.confirmLockUser)) {
        window.vue.ajaxRequest('get', window.location.origin + '/u/' + id + '/deactivate', {}, function (response) {
            alert(response.msg);

            if ((typeof response.logout !== 'undefined') && (response.logout)) {
                location.reload();
            }
        });
    }
};

window.deleteUserAccount = function () {
    let pw = prompt(window.vue.translationTable.confirmDeleteOwnAccount);
    if (pw.length > 0) {
        window.vue.ajaxRequest('post', window.location.origin + '/u/deleteownaccount', { password: pw }, function (response) {
            alert(response.msg);

            if (response.code == 200) {
                location.reload();
            }
        });
    }
};

window.lockComment = function (id) {
    if (confirm(window.vue.translationTable.confirmLockComment)) {
        window.vue.ajaxRequest('get', window.location.origin + '/c/' + id + '/lock', {}, function (response) {
            alert(response.msg);
        });
    }
};

window.toggleOverlay = function(name) {
    let obj = document.getElementById('overlay-' + name);
    if ((obj.style.display === 'none') || (obj.style.display == '')) {
        obj.style.display = 'unset';
    } else {
        obj.style.display = 'none';
    }
}

window.setTheme = function(theme) {
    if ((theme !== null) && (typeof theme === 'string') && (theme.length > 0)) {
        let expDate = new Date(Date.now() + 1000 * 60 * 60 * 24 * 365);
        document.cookie = 'theme=' + theme + '; expires=' + expDate.toUTCString() + '; path=/;';

        location.reload();
    }
};

window.fetchStorySelection = function() {
    window.vue.ajaxRequest('get', window.location.origin + '/stories/selection', {}, function(response){
        if (response.code === 200) {
            response.data.forEach(function(elem, index){
                let html = `
               <div class="stories-item" id="story-item-` + elem.user.id + `">
                    <div class="stories-item-avatar" id="stories-item-` + elem.user.id + `">
                        <img src="` + window.location.origin + '/gfx/avatars/' + elem.user.avatar + `" onclick="window.viewStory(` + elem.user.id + `)"/>
                    </div>

                    <div class="stories-item-username">
                        ` + ((elem.user.username.length > 8) ? elem.user.username.substr(0, 7) + '...' : elem.user.username) + `
                    </div>
                </div>
               `;

                document.getElementById('stories').innerHTML += html;
            });
        } else {
            document.getElementById('stories').innerHTML = elem.msg;
        }
    });
};

window.viewStory = function(userId){
    window.currentStoryData = null;

    let orig = document.getElementById('stories-item-' + userId).innerHTML;
    document.getElementById('stories-item-' + userId).innerHTML = '&nbsp;&nbsp;<i class="fas fa-spinner fa-spin"></i>';

    window.vue.ajaxRequest('get', window.location.origin + '/stories/view/' + userId, {}, function(response){
        document.getElementById('stories-item-' + userId).innerHTML = orig;

        if (response.code === 200) {
            window.currentStoryData = response.data;
            window.currentStoryIndex = 0;

            window.showStoryPost(window.currentStoryIndex);

            document.getElementById('story-title').innerHTML = 'Story';

            window.vue.bShowViewStory = true;

            document.getElementById('story-item-' + userId).remove();
        } else {
            alert(response.msg);
        }
    });
};

window.showStoryPost = function(index) {
  if ((window.currentStoryData !== null) && (index >= 0) && (index < window.currentStoryData.length)) {
      if (window.currentStoryData[index].type === 1) {
          document.getElementById('story-content').style.backgroundColor = 'unset';
          document.getElementById('story-content').style.backgroundImage = 'url(' + window.location.origin + '/gfx/stories/' + window.currentStoryData[index].background + ')';
          document.getElementById('story-content').style.backgroundSize = 'contain';
          document.getElementById('story-content').style.backgroundRepeat = 'no-repeat';
      } else {
          document.getElementById('story-content').style.backgroundImage = 'unset';
          document.getElementById('story-content').style.backgroundSize = 'unset';
          document.getElementById('story-content').style.backgroundRepeat = 'unset';
          document.getElementById('story-content').style.backgroundColor = window.currentStoryData[index].background;
      }

      document.getElementById('story-message').innerHTML = window.currentStoryData[index].message;
      document.getElementById('story-message').style.color = window.currentStoryData[index].text_color;
  }
};

window.postStory = function() {
    if (window.addStoryTabPage === 1) {
        window.vue.ajaxRequest('post', window.location.origin + '/stories/add/image', {
            image: document.getElementById('story-add-file-name').value,
            message: document.getElementById('story-add-file-text').value,
            color: document.getElementById('story-add-file-color').value
        }, function(response){
            alert(response.msg);

            if (response.code === 200) {
                window.vue.bShowAddStory = false;
            }
        });
    } else if (window.addStoryTabPage === 2) {
        window.vue.ajaxRequest('post', window.location.origin + '/stories/add/text', {
            message: document.getElementById('story-add-message-text').value,
            color: document.getElementById('story-add-message-color').value,
            bgcolor: document.getElementById('story-add-message-bgcolor').value
        }, function(response){
            alert(response.msg);

            if (response.code === 200) {
                window.vue.bShowAddStory = false;
            }
        });
    }
};

window.setStoryImage = function(obj) {
    let formData = new FormData();
    formData.append('image', obj.files[0]);

    document.getElementById('add-story-content').innerHTML = '<div id="add-story-message"><i class="fas fa-spinner fa-spin"></i></div>';

    window.vue.ajaxRequest('post', window.location.origin + '/stories/image/upload',
        formData
    , function(response){
       if (response.code === 200) {
           document.getElementById('add-story-content').innerHTML = '<div id="add-story-message">';
           document.getElementById('story-add-file-name').value = response.name;
           document.getElementById('add-story-content').style.backgroundImage = 'url(' + window.location.origin + '/gfx/stories/' + response.name + ')';
           document.getElementById('add-story-content').style.backgroundSize = 'contain';
           document.getElementById('add-story-content').style.backgroundRepeat = 'no-repeat';
       } else {
           alert(response.msg);
       }
    }, function(){},
        {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
};

window.clearStoryInput = function() {
    document.getElementById('add-story-message').innerHTML = '';
    document.getElementById('add-story-content').style.backgroundImage = 'unset';
    document.getElementById('add-story-content').style.backgroundColor = 'unset';
    document.getElementById('story-add-file-file').value = '';
    document.getElementById('story-add-file-text').value = '';
    document.getElementById('story-add-message-text').value = '';
};

//Make vue instance available globally
window.vue = vue;

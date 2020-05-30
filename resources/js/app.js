/*
    Danigram (dnyDanigram) developed by Daniel Brendel

        (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
*/

//Make Vue instance
let vue = new Vue({
    el: '#main',

    data: {
        bShowRecover: false,
        bShowRegister: false,
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
            }
        },

        clickedCookieConsentButton: function () {
            //Client clicked on Ok-button so set cookie to not show consent anymore

            let curDate = new Date(Date.now() + 1000 * 60 * 60 * 24 * 365);
            document.cookie = 'cookieconsent=1; expires=' + curDate.toUTCString() + ';';

            document.getElementById('cookie-consent').style.display = 'none';
        },

        setPostFetchType: function (type) {
            let curDate = new Date(Date.now() + 1000 * 60 * 60 * 24 * 365);
            document.cookie = 'fetch_type=' + type + '; expires=' + curDate.toUTCString() + ';';
        },

        getPostFetchType: function () {
            let cookies = document.cookie.split(';');

            for (let i = 0; i < cookies.length; i++) {
                if (cookies[i].indexOf('fetch_type') !== -1) {
                    return cookies[i].substr(cookies[i].indexOf('=') + 1);
                }
            }

            this.setPostFetchType(1);

            return 1;
        },

        ajaxRequest: function (method, url, data = {}, successfunc = function(data){}, finalfunc = function(){})
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

            func(url, data)
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

        toggleHeart: function(elemId) {
            let obj = document.getElementById('heart-' + elemId);

            this.ajaxRequest('post', window.location.origin + '/p/heart', { post: elemId, value: !parseInt(obj.getAttribute('data-value'))}, function(response) {
               if (response.code === 200) {
                   if (response.value) {
                       obj.classList.remove('far', 'fa-heart');
                       obj.classList.add('fas', 'fa-heart', 'is-hearted');
                   } else {
                       obj.classList.remove('fas', 'fa-heart', 'is-hearted');
                       obj.classList.add('far', 'fa-heart');
                   }

                   obj.setAttribute('data-value', ((response.value) ? '1' : '0'));

                   document.getElementById('count-' + elemId).innerHTML = response.count;
               }
            });
        }
    }
});

window.renderPost = function(elem)
{
    let hashTags = '';
    let hashArr = elem.hashtags.trim().split(' ');
    hashArr.forEach(function (elem, index) {
        hashTags += '<a href="' + window.location.origin + '/t/' + elem + '">#' + elem + '</a>&nbsp;';
    });

    let html = `
                            <div class="member-form">
                            <div class="show-post-header">
                                <div class="show-post-avatar">
                                    <img src="` + window.location.origin + '/gfx/avatars/' + elem.user.avatar + `" class="is-pointer" onclick="location.href='` + window.location.origin + `/u/` + elem.user.id + `'">
                                </div>

                                <div class="show-post-userinfo">
                                    <div>` + elem.user.username + `</div>
                                    <div title="` + elem.created_at + `">` + elem.diffForHumans + `</div>
                                </div>
                            </div>

                            <div class="show-post-image">
                                <img class="is-pointer" src="` + window.location.origin + `/gfx/posts/` + elem.image_thumb + `" onclick="location.href='` + window.location.origin + '/p/' + elem.id + `'">
                            </div>

                            <div class="show-post-attributes">
                                <div class="is-inline-block"><i id="heart-` + elem.id + `" class="` + ((elem.userHearted) ? 'fas fa-heart is-hearted': 'far fa-heart') + ` is-pointer" onclick="window.vue.toggleHeart(` + elem.id + `)" data-value="` + ((elem.userHearted) ? '1' : '0') + `"></i> <span id="count-` + elem.id + `">` + elem.hearts + `</span></div>
                                <div class="is-inline-block is-right" style="float:right;"><a href="` + window.location.origin + `/p/` + elem.id + `#thread">` + elem.comment_count + ` comments</a></div>
                            </div>

                            <div class="show-post-description">
                                ` + elem.description + `
                                       </div>

                                       <div class="show-post-hashtags">
                                        ` + hashTags + `
                                       </div>
                                   </div>
                        `;
    return html;
};

window.renderThread = function(elem) {
    let html = `
        <a name="` + elem.id + `"></a>

        <div class="thread-header">
            <div class="thread-header-avatar is-inline-block">
                <img width="24" height="24" src="` + window.location.origin + `/gfx/avatars/` + elem.user.avatar + `" class="is-pointer" onclick="location.href = '';" title="">
            </div>

            <div class="thread-header-info is-inline-block">
                <div>` + elem.user.username + `</div>
                <div title="` + elem.created_at + `">` + elem.diffForHumans + `</div>
            </div>
        </div>

        <div class="thread-text">
            ` + elem.text + `
        </div>

        <div class="thread-footer">
            <div class="thread-footer-hearts"><i class="far fa-heart"></i>&nbsp;` + elem.hearts + `</div>
            <div class="thread-footer-options">
                ` + ((elem.ownerOrAdmin) ? '<a href="">Edit</a> | <a href="">Delete</a> |' : '') + `
                <a href="">Report</a>
            </div>
        </div>
    `;

    return html;
};

//Make vue instance available globally
window.vue = vue;

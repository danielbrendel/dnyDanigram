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

            this.setPostFetchType(2);

            return 2;
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
            alert('Text has been copyied to clipboard!');
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

window.renderPost = function(elem)
{
    let hashTags = '';
    let hashArr = elem.hashtags.trim().split(' ');
    hashArr.forEach(function (elem, index) {
        hashTags += '<a href="' + window.location.origin + '/t/' + elem + '">#' + elem + '</a>&nbsp;';
    });

    let html = `
                            <div class="member-form">
                            <div class="show-post-header is-default-padding">
                                <div class="show-post-avatar">
                                    <img src="` + window.location.origin + '/gfx/avatars/' + elem.user.avatar + `" class="is-pointer" onclick="location.href='` + window.location.origin + `/u/` + elem.user.id + `'" width="32" height="32">
                                </div>

                                <div class="show-post-userinfo">
                                    <div>` + elem.user.username + `</div>
                                    <div title="` + elem.created_at + `">` + elem.diffForHumans + `</div>
                                </div>

                                <div class="show-post-options is-inline-block">
                                    <div class="dropdown is-right" id="post-options-` + elem.id + `">
                                        <div class="dropdown-trigger">
                                            <i class="fas fa-ellipsis-v is-pointer" onclick="window.vue.togglePostOptions(document.getElementById('post-options-` + elem.id + `'));"></i>
                                        </div>
                                        <div class="dropdown-menu" role="menu">
                                            <div class="dropdown-content">
                                                <a onclick="window.vue.togglePostOptions(document.getElementById('post-options-` + elem.id + `'));" href="whatsapp://send?text=` + window.location.origin + `/p/` + elem.id + ` ` + ((elem.description.length > MAX_SHARE_TEXT_LENGTH) ? elem.description.substr(0, MAX_SHARE_TEXT_LENGTH) + '...' : elem.description) + `" class="dropdown-item">
                                                    <i class="far fa-copy"></i>&nbsp;Share via WhatsApp
                                                </a>
                                                <a onclick="window.vue.togglePostOptions(document.getElementById('post-options-` + elem.id + `'));" href="https://twitter.com/share?url=` + encodeURIComponent(window.location.origin + '/p/' + elem.id) + `&text=` + ((elem.description.length > MAX_SHARE_TEXT_LENGTH) ? elem.description.substr(0, MAX_SHARE_TEXT_LENGTH) + '...' : elem.description) + `" class="dropdown-item">
                                                    <i class="fab fa-twitter"></i>&nbsp;Share via Twitter
                                                </a>
                                                <a onclick="window.vue.togglePostOptions(document.getElementById('post-options-` + elem.id + `'));" href="https://www.facebook.com/sharer/sharer.php?u=` + window.location.origin + `/p/` + elem.id + `" class="dropdown-item">
                                                    <i class="fab fa-facebook"></i>&nbsp;Share via Facebook
                                                </a>
                                                <a onclick="window.vue.togglePostOptions(document.getElementById('post-options-` + elem.id + `'));" href="mailto:name@domain.com?body=` + window.location.origin + `/p/` + elem.id + ` ` + ((elem.description.length > MAX_SHARE_TEXT_LENGTH) ? elem.description.substr(0, MAX_SHARE_TEXT_LENGTH) + '...' : elem.description) + `" class="dropdown-item">
                                                    <i class="far fa-envelope"></i>&nbsp;Share via E-Mail
                                                </a>
                                                <a onclick="window.vue.togglePostOptions(document.getElementById('post-options-` + elem.id + `'));" href="sms:000000000?body=` + window.location.origin + `/p/` + elem.id + ` ` + ((elem.description.length > MAX_SHARE_TEXT_LENGTH) ? elem.description.substr(0, MAX_SHARE_TEXT_LENGTH) + '...' : elem.description) + `" class="dropdown-item">
                                                    <i class="fas fa-sms"></i>&nbsp;Share via SMS
                                                </a>
                                                <a href="javascript:void(0)" onclick="window.vue.copyToClipboard('` + window.location.origin + `/p/` + elem.id + ` ` + ((elem.description.length > MAX_SHARE_TEXT_LENGTH) ? elem.description.substr(0, MAX_SHARE_TEXT_LENGTH) + '...' : elem.description) + `'); window.vue.togglePostOptions(document.getElementById('post-options-` + elem.id + `'));" class="dropdown-item">
                                                    <i class="far fa-copy"></i>&nbsp;Copy link
                                                </a>
                                                <hr class="dropdown-divider">
                                                <a href="javascript:void(0)" onclick="reportPost(` + elem.id + `); window.vue.togglePostOptions(document.getElementById('post-options-` + elem.id + `'));" class="dropdown-item">
                                                    Report
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="show-post-image">
                                <img class="is-pointer" src="` + window.location.origin + `/gfx/posts/` + elem.image_thumb + `" onclick="location.href='` + window.location.origin + '/p/' + elem.id + `'">
                            </div>

                            <div class="show-post-attributes is-default-padding-left is-default-padding-right">
                                <div class="is-inline-block"><i id="heart-ent_post-` + elem.id + `" class="` + ((elem.userHearted) ? 'fas fa-heart is-hearted': 'far fa-heart') + ` is-pointer" onclick="window.vue.toggleHeart(` + elem.id + `, 'ENT_POST')" data-value="` + ((elem.userHearted) ? '1' : '0') + `"></i> <span id="count-ent_post-` + elem.id + `">` + elem.hearts + `</span></div>
                                <div class="is-inline-block is-right" style="float:right;"><a href="` + window.location.origin + `/p/` + elem.id + `#thread">` + elem.comment_count + ` comments</a></div>
                            </div>

                            <div class="show-post-description is-default-padding">
                                ` + elem.description + `
                                       </div>

                                       <div class="show-post-hashtags is-default-padding">
                                        ` + hashTags + `
                                       </div>
                                   </div>
                        `;
    return html;
};

window.renderThread = function(elem, adminOrOwner = false) {
    let options = '';

    if (adminOrOwner) {
        options = `
            <a onclick="showEditComment(` + elem.id + `); window.vue.toggleCommentOptions(document.getElementById('thread-options-` + elem.id + `'));" href="whatsapp://send?text=\` + window.location.origin + \`/p/\` + elem.id + \` \` + ((elem.description.length > MAX_SHARE_TEXT_LENGTH) ? elem.description.substr(0, MAX_SHARE_TEXT_LENGTH) + '...' : elem.description) + \`" class="dropdown-item">
                <i class="far fa-edit"></i>&nbsp;Edit
            </a>
            <a onclick="deleteComment(` + elem.id + `); window.vue.toggleCommentOptions(document.getElementById('thread-options-` + elem.id + `'));" class="dropdown-item">
                <i class="fas fa-times"></i>&nbsp;Delete
            </a>
            <hr class="dropdown-divider">
        `;
    }

    let html = `
        <div id="thread-` + elem.id + `">
            <a name="` + elem.id + `"></a>

            <div class="thread-header">
                <div class="thread-header-avatar is-inline-block">
                    <img width="24" height="24" src="` + window.location.origin + `/gfx/avatars/` + elem.user.avatar + `" class="is-pointer" onclick="location.href = '';" title="">
                </div>

                <div class="thread-header-info is-inline-block">
                    <div>` + elem.user.username + `</div>
                    <div title="` + elem.created_at + `">` + elem.diffForHumans + `</div>
                </div>

                <div class="thread-header-options is-inline-block">
                    <div class="dropdown is-right" id="thread-options-` + elem.id + `">
                        <div class="dropdown-trigger">
                            <i class="fas fa-ellipsis-v is-pointer" onclick="window.vue.togglePostOptions(document.getElementById('thread-options-` + elem.id + `'));"></i>
                        </div>
                        <div class="dropdown-menu" role="menu">
                            <div class="dropdown-content">
                                ` + options + `

                                <a href="javascript:void(0)" onclick="reportComment(` + elem.id + `); window.vue.togglePostOptions(document.getElementById('thread-options-` + elem.id + `'));" class="dropdown-item">
                                    Report
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="thread-text" id="thread-text-` + elem.id + `">
                ` + elem.text + `
            </div>

            <div class="thread-footer">
                <div class="thread-footer-hearts"><i id="heart-ent_comment-` + elem.id + `" class="` + ((elem.userHearted) ? 'fas fa-heart is-hearted': 'far fa-heart') + ` is-pointer" onclick="window.vue.toggleHeart(` + elem.id + `, 'ENT_COMMENT')"></i>&nbsp;<span id="count-ent_comment-` + elem.id + `">` + elem.hearts + `</span></div>
            </div>
        </div>
    `;

    return html;
};

window.reportPost = function(id) {
  window.vue.ajaxRequest('post', window.location.origin + '/p/' + id + '/report', {}, function(response) {
    if (response.code === 200) {
        alert('The post has been reported!');
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

window.addBookmark = function(entityId, type) {
  window.vue.ajaxRequest('post', window.location.origin + '/b/add', { entityId: entityId, entType: type}, function(response) {
      if (response.code === 200) {
          document.getElementById('bookmark-' + type.toLowerCase()).innerHTML = '<a href="javascript:void(0)" onclick="removeBookmark(' + entityId + ', \'' + type + '\')">Remove bookmark</a>';
      }
  });
};

window.removeBookmark = function(entityId, type) {
    window.vue.ajaxRequest('post', window.location.origin + '/b/remove', { entityId: entityId, entType: type}, function(response) {
        if (response.code === 200) {
            document.getElementById('bookmark-' + type.toLowerCase()).innerHTML = '<a href="javascript:void(0)" onclick="addBookmark(' + entityId + ', \'' + type + '\')">Add bookmark</a>';
        }
    });
};

//Make vue instance available globally
window.vue = vue;

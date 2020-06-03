/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/app.js":
/*!*****************************!*\
  !*** ./resources/js/app.js ***!
  \*****************************/
/*! no static exports found */
/***/ (function(module, exports) {

/*
    Danigram (dnyDanigram) developed by Daniel Brendel

        (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
*/
var MAX_SHARE_TEXT_LENGTH = 15; //Make Vue instance

var vue = new Vue({
  el: '#main',
  data: {
    bShowRecover: false,
    bShowRegister: false,
    bShowEditProfile: false,
    bShowEditComment: false,
    bShowCreateFaq: false,
    bShowEditFaq: false
  },
  methods: {
    invalidLoginEmail: function invalidLoginEmail() {
      var el = document.getElementById("loginemail");

      if (el.value.length == 0 || el.value.indexOf('@') == -1 || el.value.indexOf('.') == -1) {
        el.classList.add('is-danger');
      } else {
        el.classList.remove('is-danger');
      }
    },
    invalidRecoverEmail: function invalidRecoverEmail() {
      var el = document.getElementById("recoveremail");

      if (el.value.length == 0 || el.value.indexOf('@') == -1 || el.value.indexOf('.') == -1) {
        el.classList.add('is-danger');
      } else {
        el.classList.remove('is-danger');
      }
    },
    invalidLoginPassword: function invalidLoginPassword() {
      var el = document.getElementById("loginpw");

      if (el.value.length == 0) {
        el.classList.add('is-danger');
      } else {
        el.classList.remove('is-danger');
      }
    },
    handleCookieConsent: function handleCookieConsent() {
      //Show cookie consent if not already for this client
      var cookies = document.cookie.split(';');
      var foundCookie = false;

      for (var i = 0; i < cookies.length; i++) {
        if (cookies[i].indexOf('cookieconsent') !== -1) {
          foundCookie = true;
          break;
        }
      }

      if (foundCookie === false) {
        document.getElementById('cookie-consent').style.display = 'inline-block';
      }
    },
    clickedCookieConsentButton: function clickedCookieConsentButton() {
      //Client clicked on Ok-button so set cookie to not show consent anymore
      var curDate = new Date(Date.now() + 1000 * 60 * 60 * 24 * 365);
      document.cookie = 'cookieconsent=1; expires=' + curDate.toUTCString() + ';';
      document.getElementById('cookie-consent').style.display = 'none';
    },
    setPostFetchType: function setPostFetchType(type) {
      var curDate = new Date(Date.now() + 1000 * 60 * 60 * 24 * 365);
      document.cookie = 'fetch_type=' + type + '; expires=' + curDate.toUTCString() + ';';
    },
    getPostFetchType: function getPostFetchType() {
      var cookies = document.cookie.split(';');

      for (var i = 0; i < cookies.length; i++) {
        if (cookies[i].indexOf('fetch_type') !== -1) {
          return cookies[i].substr(cookies[i].indexOf('=') + 1);
        }
      }

      this.setPostFetchType(2);
      return 2;
    },
    ajaxRequest: function ajaxRequest(method, url) {
      var data = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
      var successfunc = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : function (data) {};
      var finalfunc = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : function () {};
      //Perform ajax request
      var func = window.axios.get;

      if (method == 'post') {
        func = window.axios.post;
      } else if (method == 'patch') {
        func = window.axios.patch;
      } else if (method == 'delete') {
        func = window.axios["delete"];
      }

      func(url, data).then(function (response) {
        successfunc(response.data);
      })["catch"](function (error) {
        console.log(error);
      })["finally"](function () {
        finalfunc();
      });
    },
    toggleHeart: function toggleHeart(elemId, type) {
      var obj = document.getElementById('heart-' + type.toLowerCase() + '-' + elemId);
      this.ajaxRequest('post', window.location.origin + '/heart', {
        entity: elemId,
        value: !parseInt(obj.getAttribute('data-value')),
        type: type
      }, function (response) {
        if (response.code === 200) {
          if (response.value) {
            obj.classList.remove('far', 'fa-heart');
            obj.classList.add('fas', 'fa-heart', 'is-hearted');
          } else {
            obj.classList.remove('fas', 'fa-heart', 'is-hearted');
            obj.classList.add('far', 'fa-heart');
          }

          obj.setAttribute('data-value', response.value ? '1' : '0');
          document.getElementById('count-' + type.toLowerCase() + '-' + elemId).innerHTML = response.count;
        }
      });
    },
    togglePostOptions: function togglePostOptions(elem) {
      if (elem.classList.contains('is-active')) {
        elem.classList.remove('is-active');
      } else {
        elem.classList.add('is-active');
      }
    },
    toggleCommentOptions: function toggleCommentOptions(elem) {
      if (elem.classList.contains('is-active')) {
        elem.classList.remove('is-active');
      } else {
        elem.classList.add('is-active');
      }
    },
    copyToClipboard: function copyToClipboard(text) {
      var el = document.createElement('textarea');
      el.value = text;
      document.body.appendChild(el);
      el.select();
      document.execCommand('copy');
      document.body.removeChild(el);
      alert('Text has been copyied to clipboard!');
    },
    showError: function showError() {
      document.getElementById('flash-error').style.display = 'inherit';
      setTimeout(function () {
        document.getElementById('flash-error').style.display = 'none';
      }, 3500);
    },
    showSuccess: function showSuccess() {
      document.getElementById('flash-success').style.display = 'inherit';
      setTimeout(function () {
        document.getElementById('flash-success').style.display = 'none';
      }, 3500);
    }
  }
});

window.renderPost = function (elem) {
  var adminOrOwner = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
  var hashTags = '';
  var hashArr = elem.hashtags.trim().split(' ');
  hashArr.forEach(function (elem, index) {
    hashTags += '<a href="' + window.location.origin + '/t/' + elem + '">#' + elem + '</a>&nbsp;';
  });
  var adminOptions = '';

  if (adminOrOwner) {
    adminOptions = "\n            <a href=\"javascript:void(0)\" onclick=\"lockPost(" + elem.id + "); window.vue.togglePostOptions(document.getElementById('post-options-" + elem.id + "'));\" class=\"dropdown-item\">\n                Lock\n            </a>\n        ";
  }

  var html = "\n                            <div class=\"show-post member-form\">\n                            <div class=\"show-post-header is-default-padding\">\n                                <div class=\"show-post-avatar\">\n                                    <img src=\"" + window.location.origin + '/gfx/avatars/' + elem.user.avatar + "\" class=\"is-pointer\" onclick=\"location.href='" + window.location.origin + "/u/" + elem.user.username + "'\" width=\"32\" height=\"32\">\n                                </div>\n\n                                <div class=\"show-post-userinfo\">\n                                    <div>" + elem.user.username + "</div>\n                                    <div title=\"" + elem.created_at + "\">" + elem.diffForHumans + "</div>\n                                </div>\n\n                                <div class=\"show-post-options is-inline-block\">\n                                    <div class=\"dropdown is-right\" id=\"post-options-" + elem.id + "\">\n                                        <div class=\"dropdown-trigger\">\n                                            <i class=\"fas fa-ellipsis-v is-pointer\" onclick=\"window.vue.togglePostOptions(document.getElementById('post-options-" + elem.id + "'));\"></i>\n                                        </div>\n                                        <div class=\"dropdown-menu\" role=\"menu\">\n                                            <div class=\"dropdown-content\">\n                                                <a onclick=\"window.vue.togglePostOptions(document.getElementById('post-options-" + elem.id + "'));\" href=\"whatsapp://send?text=" + window.location.origin + "/p/" + elem.id + " " + (elem.description.length > MAX_SHARE_TEXT_LENGTH ? elem.description.substr(0, MAX_SHARE_TEXT_LENGTH) + '...' : elem.description) + "\" class=\"dropdown-item\">\n                                                    <i class=\"far fa-copy\"></i>&nbsp;Share via WhatsApp\n                                                </a>\n                                                <a onclick=\"window.vue.togglePostOptions(document.getElementById('post-options-" + elem.id + "'));\" href=\"https://twitter.com/share?url=" + encodeURIComponent(window.location.origin + '/p/' + elem.id) + "&text=" + (elem.description.length > MAX_SHARE_TEXT_LENGTH ? elem.description.substr(0, MAX_SHARE_TEXT_LENGTH) + '...' : elem.description) + "\" class=\"dropdown-item\">\n                                                    <i class=\"fab fa-twitter\"></i>&nbsp;Share via Twitter\n                                                </a>\n                                                <a onclick=\"window.vue.togglePostOptions(document.getElementById('post-options-" + elem.id + "'));\" href=\"https://www.facebook.com/sharer/sharer.php?u=" + window.location.origin + "/p/" + elem.id + "\" class=\"dropdown-item\">\n                                                    <i class=\"fab fa-facebook\"></i>&nbsp;Share via Facebook\n                                                </a>\n                                                <a onclick=\"window.vue.togglePostOptions(document.getElementById('post-options-" + elem.id + "'));\" href=\"mailto:name@domain.com?body=" + window.location.origin + "/p/" + elem.id + " " + (elem.description.length > MAX_SHARE_TEXT_LENGTH ? elem.description.substr(0, MAX_SHARE_TEXT_LENGTH) + '...' : elem.description) + "\" class=\"dropdown-item\">\n                                                    <i class=\"far fa-envelope\"></i>&nbsp;Share via E-Mail\n                                                </a>\n                                                <a onclick=\"window.vue.togglePostOptions(document.getElementById('post-options-" + elem.id + "'));\" href=\"sms:000000000?body=" + window.location.origin + "/p/" + elem.id + " " + (elem.description.length > MAX_SHARE_TEXT_LENGTH ? elem.description.substr(0, MAX_SHARE_TEXT_LENGTH) + '...' : elem.description) + "\" class=\"dropdown-item\">\n                                                    <i class=\"fas fa-sms\"></i>&nbsp;Share via SMS\n                                                </a>\n                                                <a href=\"javascript:void(0)\" onclick=\"window.vue.copyToClipboard('" + window.location.origin + "/p/" + elem.id + " " + (elem.description.length > MAX_SHARE_TEXT_LENGTH ? elem.description.substr(0, MAX_SHARE_TEXT_LENGTH) + '...' : elem.description) + "'); window.vue.togglePostOptions(document.getElementById('post-options-" + elem.id + "'));\" class=\"dropdown-item\">\n                                                    <i class=\"far fa-copy\"></i>&nbsp;Copy link\n                                                </a>\n                                                <hr class=\"dropdown-divider\">\n                                                <a href=\"javascript:void(0)\" onclick=\"reportPost(" + elem.id + "); window.vue.togglePostOptions(document.getElementById('post-options-" + elem.id + "'));\" class=\"dropdown-item\">\n                                                    Report\n                                                </a>\n                                                " + adminOptions + "\n                                            </div>\n                                        </div>\n                                    </div>\n                                </div>\n                            </div>\n\n                            <div class=\"show-post-image\">\n                                <img class=\"is-pointer\" src=\"" + window.location.origin + "/gfx/posts/" + elem.image_thumb + "\" onclick=\"location.href='" + window.location.origin + '/p/' + elem.id + "'\">\n                            </div>\n\n                            <div class=\"show-post-attributes is-default-padding-left is-default-padding-right\">\n                                <div class=\"is-inline-block\"><i id=\"heart-ent_post-" + elem.id + "\" class=\"" + (elem.userHearted ? 'fas fa-heart is-hearted' : 'far fa-heart') + " is-pointer\" onclick=\"window.vue.toggleHeart(" + elem.id + ", 'ENT_POST')\" data-value=\"" + (elem.userHearted ? '1' : '0') + "\"></i> <span id=\"count-ent_post-" + elem.id + "\">" + elem.hearts + "</span></div>\n                                <div class=\"is-inline-block is-right float-right\"><a class=\"is-color-grey\" href=\"" + window.location.origin + "/p/" + elem.id + "#thread\">" + elem.comment_count + " comments</a></div>\n                            </div>\n\n                            <div class=\"show-post-description is-default-padding\">\n                                " + elem.description + "\n                                       </div>\n\n                                       <div class=\"show-post-hashtags is-default-padding\">\n                                        " + hashTags + "\n                                       </div>\n                                   </div>\n                        ";
  return html;
};

window.renderThread = function (elem) {
  var adminOrOwner = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
  var options = '';

  if (adminOrOwner) {
    options = "\n            <a onclick=\"showEditComment(" + elem.id + "); window.vue.toggleCommentOptions(document.getElementById('thread-options-" + elem.id + "'));\" href=\"whatsapp://send?text=` + window.location.origin + `/p/` + elem.id + ` ` + ((elem.description.length > MAX_SHARE_TEXT_LENGTH) ? elem.description.substr(0, MAX_SHARE_TEXT_LENGTH) + '...' : elem.description) + `\" class=\"dropdown-item\">\n                <i class=\"far fa-edit\"></i>&nbsp;Edit\n            </a>\n            <a onclick=\"lockComment(" + elem.id + "); window.vue.toggleCommentOptions(document.getElementById('thread-options-" + elem.id + "'));\" class=\"dropdown-item\">\n                <i class=\"fas fa-times\"></i>&nbsp;Lock\n            </a>\n            <hr class=\"dropdown-divider\">\n        ";
  }

  var html = "\n        <div id=\"thread-" + elem.id + "\">\n            <a name=\"" + elem.id + "\"></a>\n\n            <div class=\"thread-header\">\n                <div class=\"thread-header-avatar is-inline-block\">\n                    <img width=\"24\" height=\"24\" src=\"" + window.location.origin + "/gfx/avatars/" + elem.user.avatar + "\" class=\"is-pointer\" onclick=\"location.href = '" + window.location.origin + "/u/" + elem.user.username + "';\" title=\"\">\n                </div>\n\n                <div class=\"thread-header-info is-inline-block\">\n                    <div>" + elem.user.username + "</div>\n                    <div title=\"" + elem.created_at + "\">" + elem.diffForHumans + "</div>\n                </div>\n\n                <div class=\"thread-header-options is-inline-block\">\n                    <div class=\"dropdown is-right\" id=\"thread-options-" + elem.id + "\">\n                        <div class=\"dropdown-trigger\">\n                            <i class=\"fas fa-ellipsis-v is-pointer\" onclick=\"window.vue.togglePostOptions(document.getElementById('thread-options-" + elem.id + "'));\"></i>\n                        </div>\n                        <div class=\"dropdown-menu\" role=\"menu\">\n                            <div class=\"dropdown-content\">\n                                " + options + "\n\n                                <a href=\"javascript:void(0)\" onclick=\"reportComment(" + elem.id + "); window.vue.togglePostOptions(document.getElementById('thread-options-" + elem.id + "'));\" class=\"dropdown-item\">\n                                    Report\n                                </a>\n                            </div>\n                        </div>\n                    </div>\n                </div>\n            </div>\n\n            <div class=\"thread-text\" id=\"thread-text-" + elem.id + "\">\n                " + elem.text + "\n            </div>\n\n            <div class=\"thread-footer\">\n                <div class=\"thread-footer-hearts\"><i id=\"heart-ent_comment-" + elem.id + "\" class=\"" + (elem.userHearted ? 'fas fa-heart is-hearted' : 'far fa-heart') + " is-pointer\" onclick=\"window.vue.toggleHeart(" + elem.id + ", 'ENT_COMMENT')\"></i>&nbsp;<span id=\"count-ent_comment-" + elem.id + "\">" + elem.hearts + "</span></div>\n            </div>\n        </div>\n    ";
  return html;
};

window.renderNotification = function (elem) {
  var newItem = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
  var icon = 'fas fa-info-circle';

  if (elem.type === 'PUSH_HEARTED') {
    icon = 'far fa-heart';
  } else if (elem.type === 'PUSH_COMMENTED') {
    icon = 'far fa-comment';
  } else if (elem.type === 'PUSH_MENTIONED') {
    icon = 'fas fa-bolt';
  } else if (elem.type === 'PUSH_MESSAGED') {
    icon = 'far fa-comments';
  }

  var html = "\n        <div class=\"notification-item " + (newItem ? 'is-new-notification' : '') + "\">\n            <div class=\"notification-item-icon\"><i class=\"" + icon + "\"></i></div>\n            <div class=\"notification-item-message\">" + elem.longMsg + "</div>\n        </div>\n    ";
  return html;
};

window.renderMessageListItem = function (item) {
  var html = "\n        <div class=\"messages-item " + (!item.seen ? 'is-new-message' : '') + "\">\n            <div class=\"messages-item-avatar\">\n                <img src=\"" + window.location.origin + "/gfx/avatars/" + item.user.avatar + "\">\n            </div>\n\n            <div class=\"messages-item-name\">\n                <a href=\"" + window.location.origin + "/u/" + item.user.username + "\">" + item.user.username + "</a>\n            </div>\n\n            <div class=\"messages-item-subject\">\n                <a href=\"" + window.location.origin + "/messages/show/" + item.id + "\">" + item.subject + "</a>\n            </div>\n\n            <div class=\"messages-item-date\" title=\"" + item.created_at + "\">\n                " + item.diffForHumans + "\n            </div>\n        </div>\n    ";
  return html;
};

window.reportPost = function (id) {
  window.vue.ajaxRequest('post', window.location.origin + '/p/' + id + '/report', {}, function (response) {
    if (response.code === 200) {
      alert('The post has been reported!');
    }
  });
};

window.showEditComment = function (elemId) {
  document.getElementById('editCommentId').value = elemId;
  document.getElementById('editCommentText').value = document.getElementById('thread-text-' + elemId).innerHTML;
  window.vue.bShowEditComment = true;
};

window.editComment = function (elemId, text) {
  var oldContent = document.getElementById('thread-text-' + elemId).innerHTML;
  document.getElementById('thread-text-' + elemId).innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
  window.vue.ajaxRequest('post', window.location.origin + '/comment/edit', {
    comment: elemId,
    text: text
  }, function (response) {
    if (response.code === 200) {
      document.getElementById('thread-text-' + elemId).innerHTML = text;
    } else {
      document.getElementById('thread-text-' + elemId).innerHTML = oldContent;
      alert(response.msg);
    }
  });
};

window.deleteComment = function (elemId) {
  if (confirm('Do you really want to delete this comment?')) {
    window.vue.ajaxRequest('post', window.location.origin + '/comment/delete', {
      comment: elemId
    }, function (response) {
      if (response.code === 200) {
        document.getElementById('thread-' + elemId).remove();
      } else {
        alert(response.msg);
      }
    });
  }
};

window.reportComment = function (elemId) {
  window.vue.ajaxRequest('post', window.location.origin + '/comment/report', {
    comment: elemId
  }, function (response) {
    alert(response.msg);
  });
};

window.reportProfile = function (elemId) {
  window.vue.ajaxRequest('get', window.location.origin + '/u/' + elemId + '/report', {}, function (response) {
    alert(response.msg);
  });
};

window.reportTag = function (elemId) {
  window.vue.ajaxRequest('get', window.location.origin + '/t/' + elemId + '/report', {}, function (response) {
    alert(response.msg);
  });
};

window.addFavorite = function (entityId, type) {
  window.vue.ajaxRequest('post', window.location.origin + '/f/add', {
    entityId: entityId,
    entType: type
  }, function (response) {
    if (response.code === 200) {
      document.getElementById('favorite-' + type.toLowerCase()).innerHTML = '<a href="javascript:void(0)" onclick="removeFavorite(' + entityId + ', \'' + type + '\')">Remove favorite</a>';
    }
  });
};

window.removeFavorite = function (entityId, type) {
  window.vue.ajaxRequest('post', window.location.origin + '/f/remove', {
    entityId: entityId,
    entType: type
  }, function (response) {
    if (response.code === 200) {
      document.getElementById('favorite-' + type.toLowerCase()).innerHTML = '<a href="javascript:void(0)" onclick="addFavorite(' + entityId + ', \'' + type + '\')">Add favorite</a>';
    }
  });
};

window.clearPushIndicator = function (obj) {
  if (obj.classList.contains('is-hearted')) {
    obj.classList.remove('fas', 'is-hearted');
    obj.classList.add('far');
    obj.setAttribute('title', 'Notifications');
  }
};

window.toggleNotifications = function (ident) {
  var obj = document.getElementById(ident);

  if (obj) {
    if (obj.style.display === 'block') {
      obj.style.display = 'none';
    } else {
      obj.style.display = 'block';
    }
  }
};

window.lockPost = function (id) {
  if (confirm('Do you want to lock this post?')) {
    window.vue.ajaxRequest('get', window.location.origin + '/p/' + id + '/lock', {}, function (response) {
      alert(response.msg);
    });
  }
};

window.lockHashtag = function (id) {
  if (confirm('Do you want to lock this hashtag?')) {
    window.vue.ajaxRequest('get', window.location.origin + '/t/' + id + '/lock', {}, function (response) {
      alert(response.msg);
    });
  }
};

window.lockUser = function (id) {
  if (confirm('Do you want to lock this profile?')) {
    window.vue.ajaxRequest('get', window.location.origin + '/u/' + id + '/deactivate', {}, function (response) {
      alert(response.msg);

      if (typeof response.logout !== 'undefined' && response.logout) {
        location.href = window.location.origin;
      }
    });
  }
};

window.lockComment = function (id) {
  if (confirm('Do you want to lock this comment?')) {
    window.vue.ajaxRequest('get', window.location.origin + '/c/' + id + '/lock', {}, function (response) {
      alert(response.msg);
    });
  }
};

window.toggleOverlay = function (name) {
  var obj = document.getElementById('overlay-' + name);

  if (obj.style.display === 'none' || obj.style.display == '') {
    obj.style.display = 'unset';
  } else {
    obj.style.display = 'none';
  }
}; //Make vue instance available globally


window.vue = vue;

/***/ }),

/***/ "./resources/sass/app.scss":
/*!*********************************!*\
  !*** ./resources/sass/app.scss ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 0:
/*!*************************************************************!*\
  !*** multi ./resources/js/app.js ./resources/sass/app.scss ***!
  \*************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! E:\Projects\dnyDanigram\resources\js\app.js */"./resources/js/app.js");
module.exports = __webpack_require__(/*! E:\Projects\dnyDanigram\resources\sass\app.scss */"./resources/sass/app.scss");


/***/ })

/******/ });
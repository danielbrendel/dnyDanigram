# Danigram (dnyDanigram) developed by Daniel Brendel

(C) 2019 - 2021 by Daniel Brendel

**Version**: 1.0\
**Contact**: dbrendel1988(at)gmail(dot)com\
**GitHub**: https://github.com/danielbrendel

Released under the MIT license

## Description:
Danigram is a social network system. Users can register and share posts consisting of formatted text  
and/or images/videos using hashtags. Users can comment on posts, like posts, comments and hashtags and 
mention users. Users can add favorites for hashtags and users. There is also profile management,
member search, forum, stories, private messaging, notification system, e-mail system and more. The system 
comes with a friendly installer. For a full feature list see below.

## Features:
+ Share text
+ Share images
+ Share videos
+ Share stories
+ Comment on images
+ Heart images, comments, hashtags
+ See latest and top posts and comments
+ NSFW safety
+ Hashtag system
+ Category system
+ Dynamic profile system
+ Favorites system
+ Notification system
+ Messaging system
+ Forum system
+ E-Mail system
+ Mention system
+ Find members nearby
+ Profile finder
+ Administration
+ Maintainer system
+ Theme system
+ Twitter news integration
+ HelpRealm integration
+ Client endpoint
+ Advertising
+ Purchasable pro mode
+ Installer
+ Security
+ Responsive design
+ Testcases
+ Documentation

## System requirements
The product is being developed with the following engine versions:
+ PHP 7.4.6
+ MySQL 10.4.11-MariaDB
+ Default XAMPP 3.2.4 enabled PHP extensions

## Installation:
Place a file 'do_install' in the root directory of the project.
Then go to /install. The setup wizard will guide you through the
installation process.

## Testing
In order to run the tests successfully you need to ensure that the following test data is valid:
+ TEST_USERID: ID of an existing user
+ TEST_USERID2: ID of another existing user. May not be the same as the other user
+ TEST_USERID_NONEXISTENT: ID of a non-existing user
+ TEST_USEREMAIL: E-Mail of an existing user
+ TEST_USERNAME: Username of an existing user
+ TEST_USERPW: Password for login used together with TEST_USEREMAIL
+ TEST_ENTITY_HASHTAG: ID of an existing hashtag
+ TEST_HASHTAG_NAME: Name of an existing hashtag
+ TEST_POSTID: ID of an existing post
+ TEST_COMMENT: ID of an existing comment
+ TEST_MESSAGEID: ID of an existing message
+ TEST_COMMENTID: ID of an existing comment
+ TEST_THREADID: ID of an existing thread (parent)
+ TEST_FETCHLIMIT: The fetch limit
+ TEST_FAQID: ID of an existing FAQ item

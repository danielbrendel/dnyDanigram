# Danigram (dnyDanigram) developed by Daniel Brendel

(C) 2019 - 2020 by Daniel Brendel

**Version**: 1.0\
**Contact**: dbrendel1988(at)gmail(dot)com\
**GitHub**: https://github.com/danielbrendel

Released under the MIT license

## Description:
Danigram is a lightweight photo community platform software. Users can register and share images
using hashtags. Users can comment on posts, like posts, comments and hashtags and 
highlight users. Users can bookmark hashtags and users. There is also profile management,
notification system and e-mail system. The system comes with a friendly installer. For a full
feature list see below.

## Features:
+ Share images
+ Comment on images
+ Like images, comments, hashtags
+ See latest and top posts and comments
+ Bookmark system
+ Notification system
+ Messaging system
+ E-Mail system
+ Administration
+ Installer

## System requirements
The product is being developed with the following engine versions:
+ PHP 7.4.1
+ MySQL 10.4.11-MariaDB
+ Default PHP extensions

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
+ TEST_FETCHLIMIT: The fetch limit

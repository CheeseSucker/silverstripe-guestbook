# silverstripe-guestbook #

SilverStripe 3.1 Guestbook module.

## Features ##

 - Emoticons
 - Pagination
 - Moderators can comment on entries
 - Edit and delete entries
 - E-mail address protection
 - Spam protection


## TODO ##
 - [ ] Make it translatable
 - [ ] Fix links for edit and delete.
 - [ ] Administration page should be for one guestbook.
 - [ ] Unit tests

## Installation ##

Make sure the folder is named **guestbook**, otherwise CSS and JavaScript won't
be included correctly.

### Manual ###

Copy the files to a subfolder **guestbook** in your SilverStripe project.
Then run `/dev/build?flush=all`

### Composer ###
`composer require "cheesesucker/guestbook:dev-master"`
Then run `/dev/build?flush=all`


## Spam protection ##

In order to use spam protection, you need [a spam protection module](https://github.com/silverstripe/silverstripe-spamprotection).


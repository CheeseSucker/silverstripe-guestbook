# silverstripe-guestbook #

SilverStripe 3.1 Guestbook module.

## Features ##

 - Emoticons
 - Pagination
 - Moderators can comment on entries
 - E-mail address protection
 - Spam protection
 - Flooding protection
 
## Screenshot ##

![Guestbook](http://i.imgur.com/4pPSlqY.png "Guestbook")

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


## License ##

   Copyright (c) 2014, CheeseSucker All rights reserved.

   Redistribution and use in source and binary forms, with or without
   modification, are permitted provided that the following conditions are
   met:

   1. Redistributions of source code must retain the above copyright
   notice, this list of conditions and the following disclaimer.

   2. Redistributions in binary form must reproduce the above copyright
   notice, this list of conditions and the following disclaimer in the
   documentation and/or other materials provided with the distribution.

   3. Neither the name of the copyright holder nor the names of its
   contributors may be used to endorse or promote products derived from
   this software without specific prior written permission.

   THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS
   IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED
   TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A
   PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
   HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
   SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED
   TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
   PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
   LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
   NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
   SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.


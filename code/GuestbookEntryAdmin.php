<?php

class GuestbookEntryAdmin extends ModelAdmin
{
    private static $managed_models = array('GuestbookEntry');
    private static $url_segment = 'guestbook';
    private static $menu_title = 'Guestbook';
}

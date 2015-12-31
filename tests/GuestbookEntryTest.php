<?php

class GuestbookEntryTest extends SapphireTest
{
    public function testFormattedText__enableEmoticonsIsTrue__shouldReplaceEmoticons()
    {
        $guestbook = new GuestbookPage();
        $guestbook->EnableEmoticons = true;
        $guestbookEntry = new GuestbookEntry__WithGuestbook($guestbook);
        
        $text = 'We have a smiley. :) Some more text.';
        $result = $guestbookEntry->FormattedText($text);

        $this->assertContains('img', $result);
    }

    public function testFormattedText__enableEmoticonsIsFalse__shouldNotReplaceEmoticons()
    {
        $guestbook = new GuestbookPage();
        $guestbook->EnableEmoticons = false;
        $guestbookEntry = new GuestbookEntry__WithGuestbook($guestbook);

        $text = 'We have a smiley. :) Some more text.';
        $result = $guestbookEntry->FormattedText($text);

        $this->assertEquals($text, $result);
    }
}

class GuestbookEntry__WithGuestbook extends GuestbookEntry
{
    private $guestbook;
    
    public function __construct(GuestbookPage $guestbook = null)
    {
        $this->guestbook = $guestbook;
        parent::__construct();
    }


    public function Guestbook()
    {
        return $this->guestbook;
    }
}

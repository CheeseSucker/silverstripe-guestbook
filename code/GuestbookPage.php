<?php

class GuestbookPage extends Page {
	private static $db = array(
		'PostingCooldown' => 'Int',
		'PostPerPage' => 'Int'
	);

	private static $has_many = array(
		'Entries' => 'GuestbookEntry'
	);

	public function getCMSFields() {
		parent::getCMSFields();
	}
}


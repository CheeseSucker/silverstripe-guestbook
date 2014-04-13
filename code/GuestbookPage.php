<?php

class GuestbookPage extends Page {
	private static $db = array(
		'EntriesPerPage' => 'Int',
		'EnableEmoticons' => 'Boolean',
		'ProtectEmails' => 'Boolean',
		'UseSpamProtection' => 'Boolean'
	);

	private static $defaults = array(
		'EntriesPerPage' => 20,
		'EnableEmoticons' => true,
		'ProtectEmails' => true,
		'UseSpamProtection' => false
	);

	private static $has_many = array(
		'Entries' => 'GuestbookEntry',
	);

	public function canSeeEmailAddresses() {
		return !$this->ProtectEmails || Member::currentUser() || Session::get('Guestbook-ShowEmails');
	}

	public function getCMSFields() {
		$fields = parent::getCMSFields();
		/* @var $fields FieldList  */
		$fields->addFieldToTab('Root', new TextField('EntriesPerPage'), 'Content');
		$fields->addFieldToTab('Root', new CheckboxField('EnableEmoticons'), 'Content');
		$fields->addFieldToTab('Root', new CheckboxField('ProtectEmails'), 'Content');
		$fields->addFieldToTab('Root', new CheckboxField('UseSpamProtection'), 'Content');
		return $fields;
	}

	public function validate() {
		$result = parent::validate();
		/* @var $result ValidationResult */
		if ($this->EntriesPerPage <= 0) {
			$result->error('EntriesPerPage must be greater than zero. Was ' . $this->EntriesPerPage);
		}
		return $result;
	}

	public function canEdit($member = null) {
		return GuestbookPage_Controller::isModerator($member);
	}

	public function canDelete($member = null) {
		return GuestbookPage_Controller::isModerator($member);
	}
}

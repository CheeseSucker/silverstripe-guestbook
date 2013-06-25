<?php

class GuestbookPage extends Page {
	private static $db = array(
		'EntriesPerPage' => 'Int',
		'EnableEmoticons' => 'Boolean',
		'ProtectEmails' => 'Boolean',
	);

	private static $defaults = array(
		'EntriesPerPage' => 20,
		'EnableEmoticons' => true,
		'ProtectEmails' => true,
	);

	private static $has_many = array(
		'Entries' => 'GuestbookEntry',
	);

	public function getCMSFields() {
		$fields = parent::getCMSFields();
		/* @var $fields FieldList  */
		$fields->addFieldToTab('Root', new TextField('EntriesPerPage'), 'Content');
		$fields->addFieldToTab('Root', new CheckboxField('EnableEmoticons'), 'Content');
		$fields->addFieldToTab('Root', new CheckboxField('ProtectEmails'), 'Content');
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

	public function canView($member = null) {
		return Permission::check('GUESTBOOK_VIEW', "any", $member);
	}

	public function canCreate($member = null) {
		return Permission::check('GUESTBOOK_CREATE', "any", $member);
	}

	public function canEdit($member = null) {
		return Permission::check('GUESTBOOK_EDIT', "any", $member);
	}

	public function canDelete($member = null) {
		return Permission::check('GUESTBOOK_DELETE', "any", $member);
	}

	public function canSeeEmailAddresses() {
		return !$this->ProtectEmails || Member::currentUser() || Session::get('Guestbook-ShowEmails');
	}

	public function providePermissions() {
		return array(
			'GUESTBOOK_VIEW' => 'Read an article object',
			'GUESTBOOK_CREATE' => 'Create an article object',
			'GUESTBOOK_EDIT' => 'Edit an article object',
			'GUESTBOOK_DELETE' => 'Delete an article object',
		);
	}
}

<?php

class GuestbookPage extends Page {
	private static $db = array(
		'EntriesPerPage' => 'Int',
		'EnableEmoticons' => 'Boolean',
		'ProtectEmails' => 'Boolean',
		'UseSpamProtection' => 'Boolean',
		'FloodLimit' => 'Int',
	);

	private static $defaults = array(
		'EntriesPerPage' => 20,
		'EnableEmoticons' => true,
		'ProtectEmails' => true,
		'UseSpamProtection' => false,
		'FloodLimit' => 60,
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
		$fields->addFieldToTab('Root', new TextField('FloodLimit'), 'Content');
		$fields->addFieldToTab('Root', new CheckboxField('EnableEmoticons'), 'Content');
		$fields->addFieldToTab('Root', new CheckboxField('ProtectEmails'), 'Content');
		$fields->addFieldToTab('Root', new CheckboxField('UseSpamProtection'), 'Content');
		return $fields;
	}

	public function validate() {
		$result = parent::validate();
		/* @var $result ValidationResult */
		if ($this->EntriesPerPage <= 0) {
			$message = _t("GuestbookPage.ENTRIESPERPAGEERROR", 'EntriesPerPage must be greater than zero. Was {count}.', "", array('count' => $this->EntriesPerPage));
			$result->error($message);
		}
		return $result;
	}

	public function canEdit($member = null) {
		return GuestbookPage_Controller::isModerator($member);
	}

	public function canDelete($member = null) {
		return GuestbookPage_Controller::isModerator($member);
	}

	public function fieldLabels($includerelations = true) {
		$labels = parent::fieldLabels($includerelations);

		$labels['EntriesPerPage'] = _t('GuestbookPage.EntriesPerPage', "Entries per page");
		$labels['EnableEmoticons'] = _t('GuestbookPage.EnableEmoticons', "Enable emoticons");
		$labels['ProtectEmails'] = _t('GuestbookPage.ProtectEmails', "Protect email addresses");
		$labels['UseSpamProtection'] = _t('GuestbookPage.UseSpamProtection', "Use spam protection");
		$labels['FloodLimit'] = _t('GuestbookPage.FloodLimit', "Flood protection: Seconds between posts from same IP");

		return $labels;
   }
}

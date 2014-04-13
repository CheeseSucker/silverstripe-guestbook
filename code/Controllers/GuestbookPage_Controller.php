<?php

/**
 * Description of GuestbookPage_Controller
 *
 * @author Hkn
 */
class GuestbookPage_Controller extends Page_Controller implements PermissionProvider {
	private static $allowed_actions = array(
			'postEntry',
			'unlockemails',
			'EmailProtectionForm',
			'doDelete',
		);

	/**
	* Returns a paginated list of all pages in the site.
	*/
   public function PaginatedEntries() {
	   $list = new PaginatedList($this->Entries(), $this->request);
	   $list->setPageLength($this->EntriesPerPage);
	   return $list;
   }

   public function Moderator($member = null) {
	   return self::isModerator($member);
   }

   static public function isModerator($member = null) {
	   return Permission::check('GUESTBOOK_MODERATE', "any", $member);
   }

   public function NewEntryForm() {
		// Create fields
		$fields = new FieldList(
				new TextField('Name'), 
				new EmailField('Email'),
				TextField::create('Website')->setAttribute('type', 'url'),
				new TextareaField("Message")
		);

		if ($this->EnableEmoticons) {
			$smileyButtons = $this->SmileyButtons("Form_NewEntryForm_Message");
			$smileyField = new LiteralField("Smileys", $smileyButtons);
			$fields->add($smileyField);
		}

		// Create actions
		$actions = new FieldList(
				new FormAction('postEntry', 'Post')
		);

		$validator = new RequiredFields('Name', 'Message');

		return new Form($this, 'NewEntryForm', $fields, $actions, $validator);
	}

	public function Smileys() {
		return new ArrayList(GuestbookEntry::Smileys());
	}

	public function postEntry(array $data, Form $form) {
		if (!empty($data['Website'])) {
			if (!filter_var($data['Website'], FILTER_VALIDATE_URL)) {
				$form->addErrorMessage('Website', "Invalid format for website.", 'bad');
				return $this->redirectBack();
			}
		}

		$FloodLimit = 60 * 3;
		if (Session::get("GuestbookPosted") > time() - $FloodLimit) {
			$form->sessionMessage("You have already posted the last " . $FloodLimit . " seconds. Please wait.", 'bad');
			return $this->redirectBack();
		}

		$entry = GuestbookEntry::create();
		$entry->GuestbookID = $this->ID;
		$form->saveInto($entry);
		$entry->write();

		$form->sessionMessage("Entry has been saved.", 'good');

		Session::set('GuestbookPosted', time());

		return $this->redirectBack();
	}

	public function EmailProtectionForm() {
		// TODO: Make this a recaptcha form
		$fields = new FieldList(
					new LiteralField('Question', "What is 2 + 3?"),
					new TextField('Answer')
				);
		$actions = new FieldList(FormAction::create('dounlockemails'));
		$validator = new RequiredFields('Answer');

		$form = new Form($this, 'EmailProtectionForm', $fields, $actions, $validator);
		return $form;
	}

	public function unlockemails() {
		if ($this->canSeeEmailAddresses()) {
			return $this->redirect($this->Link());
		}

		return $this;
	}

	public function dounlockemails(array $data, Form $form) {
		$answer = $data['Answer'];
		if ($answer != 5) {
			$form->addErrorMessage('Answer', 'Wrong answer.', 'bad');
			return $this->redirectBack();
		}

		Session::set('Guestbook-ShowEmails', true);

		// Add a flash message for the user
		if (method_exists('Page_Controller', 'addMessage')) {
			Page_Controller::addMessage('Successfully unlocked E-mail addresses', 'Success');
		}

		return $this->redirect($this->Link());
	}

	public function SmileyButtons($fieldID) {
		return $this->renderWith("SmileyButtons", array('FieldID' => $fieldID));
	}

	public function providePermissions() {
		return array(
			'GUESTBOOK_MODERATE' => 'Edit guestbook entries',
		);
	}

	public function doDelete(SS_HTTPRequest $request) {
		// TODO: Use POST and check form token.
		// The current implementation is vulnerable to code such as:
		// <img src="example.org/guestbook/doDelete?entry=123" alt="" />
		if (!Permission::check('GUESTBOOK_DELETEENTRY')) {
			Security::permissionFailure($this, "You do not have permission to delete entries.");
			return;
		}
		$entryId = $request->getVar('entry');
		if ($entryId == null || DataObject::get_by_id('GuestbookEntry', $entryId) == null) {
			return;
		}

		DataObject::delete_by_id( 'GuestbookEntry', $entryId);
		self::addMessage("Deleted guestbook entry #$entryId.", "Success");
		$this->redirectBack();
	}
}


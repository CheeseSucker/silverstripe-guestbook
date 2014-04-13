<?php

/**
 * Description of GuestbookPage_Controller
 *
 * @author Hkn
 */
class GuestbookPage_Controller extends Page_Controller implements PermissionProvider {
	private static $allowed_actions = array(
			'NewEntryForm',
			'postEntry',
			'unlockemails',
			'EmailProtectionForm',
			'doDelete' => '->canDelete',
		);

	/**
	* Returns a paginated list of all pages in the site.
	*/
   public function PaginatedEntries() {
	   $list = new PaginatedList($this->Entries(), $this->request);
	   $list->setPageLength($this->EntriesPerPage);
	   return $list;
   }

   	public function providePermissions() {
		return array(
			'GUESTBOOK_MODERATE' => 'Edit guestbook entries',
		);
	}

   public function Moderator($member = null) {
	   return self::isModerator($member);
   }

   static public function isModerator($member = null) {
	   return Permission::check('GUESTBOOK_MODERATE', "any", $member);
   }

	public function Smileys() {
		return new ArrayList(GuestbookEntry::Smileys());
	}

	public function SmileyButtons($fieldID) {
		return $this->renderWith("SmileyButtons", array('FieldID' => $fieldID));
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

		$form = new Form($this, 'NewEntryForm', $fields, $actions, $validator);
		$form->setRedirectToFormOnValidationError(true); 
		if ($this->UseSpamProtection) {
			$form->enableSpamProtection();
		}
		return $form;
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
		$fields = new FieldList();
		$actions = new FieldList(FormAction::create('dounlockemails'));

		$form = new Form($this, 'EmailProtectionForm', $fields, $actions);
		$form->enableSpamProtection();
		return $form;
	}

	public function unlockemails() {
		if ($this->canSeeEmailAddresses()) {
			// User can already see email addresses.
			// Redirect back to guestboook.
			return $this->redirect($this->Link());
		}


		// Force a login if no spam protection module exists.
		if (Form::has_extension('FormSpamProtectionExtension') == false) {
			return Security::permissionFailure($this, "You must be logged in to see email addresses.");
		}

		// Use spam protection module
		return $this;
	}

	public function dounlockemails(array $data, Form $form) {
		if (!$form->validate()) {
			return $this->redirectBack();
		}

		Session::set('Guestbook-ShowEmails', true);

		// Add a flash message for the user
		if (method_exists('Page_Controller', 'addMessage')) {
			Page_Controller::addMessage('Successfully unlocked E-mail addresses', 'Success');
		}

		return $this->redirect($this->Link());
	}

	public function doDelete(SS_HTTPRequest $request) {
		// TODO: Use POST and check form token.
		// The current implementation is vulnerable to code such as:
		// <img src="example.org/guestbook/doDelete?entry=123" alt="" />
		if (!self::isModerator()) {
			return Security::permissionFailure($this, "You do not have permission to delete entries.");
		}
		$entryId = $request->getVar('entry');
		if ($entryId == null || DataObject::get_by_id('GuestbookEntry', $entryId) == null) {
			return;
		}

		DataObject::delete_by_id( 'GuestbookEntry', $entryId);
		if (method_exists('Page_Controller', 'addMessage')) {
			self::addMessage("Deleted guestbook entry #$entryId.", "Success");
		}
		$this->redirectBack();
	}
}


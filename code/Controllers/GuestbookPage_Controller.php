<?php

/**
 * Description of GuestbookPage_Controller
 *
 * @author Hkn
 */
class GuestbookPage_Controller extends Page_Controller {
	/**
	* Returns a paginated list of all pages in the site.
	*/
   public function PaginatedEntries() {
	   $list = new PaginatedList($this->Entries(), $this->request);
	   $list->setPageLength($this->EntriesPerPage);
	   return $list;
   }

   public function Moderator() {
	   return true;
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

	public function SmileyButtons($fieldID) {
		return $this->renderWith("SmileyButtons", array('FieldID' => $fieldID));
	}
}


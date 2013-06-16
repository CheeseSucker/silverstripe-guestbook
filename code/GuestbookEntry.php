<?php

class Smiley extends ViewableData {
	private $symbol;
	private $image;
	
	public function __construct($symbol, $image) {
		$this->symbol = $symbol;
		$this->image = $image;
	}

	public function Symbol() {
		return $this->symbol;
	}

	public function Image() {
		return self::getSmileyDir() . '/' . $this->image;
	}

	public function ImageName() {
		return $this->image;
	}

	public static function getSmileyDir() {
		$smileyDir = Director::makeRelative(realpath(dirname(__FILE__) . "/../images/smileys/"));
		$smileyDir = str_replace("\\", "/", $smileyDir);
		$smileyDir = Director::baseURL() . $smileyDir;
		return $smileyDir;
	}
}

class GuestbookEntry extends DataObject {
	public static function Smileys() {
		return array(
			new Smiley(":@", "angry.gif"),
			new Smiley(":f", "flirt.gif"),
			new Smiley("x(", "dead.gif"),
			new Smiley(":(", "frown.gif"),
			new Smiley(":h", "cool.gif"),
			new Smiley(":)", "smile.gif"),
			new Smiley(";)", "wink.gif"),
			new Smiley(":-/", "undecided.gif"),
			new Smiley(":o", "surprised.gif"),
			new Smiley(":P", "tongue.gif"),
			new Smiley(":r", "embarassed.gif"),
			new Smiley(":e", "biggrin.gif"),
			new Smiley(":D", "supergrin.gif"),
			new Smiley(':$', "money.gif"),
			new Smiley(":C", "cry.gif"),
			new Smiley(":i", "innocent.gif"),
			new Smiley(":#", "sealed.gif")
		);
	}
	
	private static $db = array(
		'PortalId' => 'Int', // This is only used for importing entries from old guestbook
		'Date' => 'SS_DateTime',
		'Name' => 'Varchar',
		'Email' => 'Varchar',
		'Website' => 'Varchar',
		'IpAddress' => 'Varchar',
		'Host' => 'Varchar',
		'Message' => 'Text',
		'Comment' => 'Text'
	);

	private static $has_one = array(
		'Guestbook' => 'GuestbookPage'
	);

	private static $default_sort = 'Date DESC';

	private static $searchable_fields = array(
		'Name', 'Email', 'Website', 'Message', 'Comment'
	);

	private static $summary_fields = array(
			'Date',
			'Name',
			'Email',
			'Message'
	   );

	public static function create() {
		$entry = new GuestbookEntry();
		$entry->Date = SS_DateTime::now()->getValue();
		$entry->IpAddress = $_SERVER['REMOTE_ADDR'];
		$entry->Host = gethostbyaddr($entry->IpAddress);
		return $entry;
	}

	public function MessageWithSmileys() {
		return $this->InsertSmileys($this->Message);
	}

	public function CommentWithSmileys() {
		return $this->InsertSmileys($this->Comment);
	}

	public function InsertSmileys($text) {
		$text = Convert::raw2xml($text);
		foreach (self::Smileys() as $smiley) {
			/* @var $smiley Smiley */
			$text = str_replace($smiley->Symbol(),
						'<img src="'.$smiley->Image().'" alt="" />',
						$text);
		}

		return nl2br($text);
	}

	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->removeByName("PortalId");
		$dateField = new DatetimeField("Date");
		$dateField->getDateField()->setConfig('showcalendar', true);
		$fields->addFieldToTab("Root.Main", $dateField);
		$fields->addFieldToTab("Root.Main", new TextField("Name"));
		$fields->addFieldToTab("Root.Main", new EmailField("Email"));
		$fields->addFieldToTab("Root.Main", new TextField("Website"));
		$fields->addFieldToTab("Root.Main", new ReadonlyField("IpAddress"));
		$fields->addFieldToTab("Root.Main", new ReadonlyField("Host"));
		$fields->addFieldToTab("Root.Main", new TextareaField("Message"));
		$fields->addFieldToTab("Root.Main", new TextareaField("Comment"));
		return $fields;
	}

	public function EditLink() {
		$id = $this->getField("ID");
		return Director::baseURL() .
				"/admin/guestbook/GuestbookEntry/EditForm/field/GuestbookEntry/item/$id/edit";
	}

	public function DeleteLink() {
		$id = $this->getField("ID");
		return Director::baseURL() .
				"/admin/guestbook/GuestbookEntry/EditForm/field/GuestbookEntry/item/$id/doDelete";
	}
}


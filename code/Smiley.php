<?php

// Namespace this class as it has common name.
// I would've namespaced the rest of the module,
// but MySQL tools didn't play well with it.
namespace CheeseSucker\mod_guestbook;

class Smiley extends \ViewableData {
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
		$smileyDir = \Director::makeRelative(realpath(dirname(__FILE__) . "/../images/smileys/"));
		$smileyDir = str_replace("\\", "/", $smileyDir);
		$smileyDir = \Director::baseURL() . $smileyDir;
		return $smileyDir;
	}
}

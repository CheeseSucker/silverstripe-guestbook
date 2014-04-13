<?php

class SmileyTest extends SapphireTest {
	public function setUp() {
		parent::setUp();
		if (!isset($_SERVER['HTTP_HOST'])) {
			$_SERVER['HTTP_HOST'] = 'http://localhost';
		}
	}

	public function testConstructorAndGetters() {
		$symbol = ':-|';
		$image = 'image.gif';
		$smiley = new Smiley($symbol, $image);

		$this->assertEquals($symbol, $smiley->Symbol());
		$this->assertEquals($image, $smiley->ImageName());
	}

	public function testReplaceSymbols__oneSymbol() {
		$this->testReplaceSymbols_nSymbols(1);
	}

	public function testReplaceSymbols__manySymbols() {
		$this->testReplaceSymbols_nSymbols(rand(5, 10));
	}

	public function testReplaceSymbols__differentSmileys() {
		$symbols = array(':-)', ':-|', ':-(');
		$tags = array();
		$smileys = array();
		for ($i = 0; $i < count($symbols); $i++) {
			$smiley = new Smiley($symbols[$i], "image$i.gif");
			$smileys[] = $smiley;
			$tags[] = $smiley->ImageTag();
		}

		$textBlocks = self::generateTextBlocks(count($smileys) + 1);
		$text = self::implode($symbols, $textBlocks);

		$expected = self::implode($tags, $textBlocks);
		foreach ($smileys as $smiley) {
			$text = $smiley->replaceSymbols($text);
		}
		$this->assertEquals($expected, $text);
	}

	/**
	 *
	 * @param type $smileyCount Number of smileys the text will contain.
	 */
	protected function testReplaceSymbols_nSymbols($smileyCount) {
		$smiley = new Smiley(":-)", 'image.gif');

		$textBlocks = self::generateTextBlocks($smileyCount + 1);

		$text = implode($smiley->Symbol(), $textBlocks);
		$expected = implode($smiley->ImageTag(), $textBlocks);
		$result = $smiley->replaceSymbols($text);

		$this->assertEquals($expected, $result);
	}

	private static function generateTextBlocks($blockCount) {
		$textBlocks = array();
		for ($i = 0; $i < $blockCount; $i++) {
			$textBlocks[$i] = self::generateText(0, 15);
		}
		return $textBlocks;
	}

	private static function generateText($min, $max) {
		$length = rand($min, $max);
		$text = "";
		for ($i = 0; $i < $length; $i++) {
			// 0 to 31 are control codes, 127 is DEL
			$letter = chr(rand(32, 126));
			if ($letter == ':') {
				// We don't want to risk creating a smiley. Try again.
				$i--;
				continue;
			}
			$text .= $letter;
		}
		return $text;
	}

	/**
	 * Alternative implementation of implode() where you can use an array of
	 * strings to use as glue.
	 *
	 * @param array $glue Must have one less element than $pieces.
	 * @param array $pieces Pieces to glue together.
	 * @return string
	 * @throws InvalidArgumentException
	 */
	private static function implode(array $glue, array $pieces) {
		if (count($pieces) == 0) {
			return "";
		}
		
		if (count($glue) != count($pieces) - 1) {
			throw new InvalidArgumentException("Glue must have one less element than pieces.");
		}
		
		$text = $pieces[0];
		for ($i = 0; $i < count($glue); $i++) {
			$text .= $glue[$i] . $pieces[$i + 1];
		}
		return $text;
	}
}

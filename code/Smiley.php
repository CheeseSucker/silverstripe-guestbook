<?php

class Smiley extends ViewableData
{
    private $symbol;
    private $image;

    public function __construct($symbol, $image)
    {
        $this->symbol = $symbol;
        $this->image = $image;
    }

    public function Symbol()
    {
        return $this->symbol;
    }

    public function Image()
    {
        return self::getSmileyDir() . '/' . $this->image;
    }

    public function ImageName()
    {
        return $this->image;
    }

    public static function getSmileyDir()
    {
        $smileyDir = Director::makeRelative(realpath(dirname(__FILE__) . "/../images/smileys/"));
        $smileyDir = str_replace("\\", "/", $smileyDir);
        $smileyDir = Director::baseURL() . $smileyDir;
        return $smileyDir;
    }

    /**
     * Return an <img> tag pointing to the smiley.s
     * @return string HTML for an image tag,
     */
    public function ImageTag()
    {
        return '<img src="' . $this->Image() . '" alt="" />';
    }

    /**
     * Replaces symbols with image tags.
     * Example: ":-)" may be replaced with "<img src='baseUrl/smileys/smile.gif' alt="" />"
     * @param type $text
     */
    public function replaceSymbols($text)
    {
        $xmlSymbol = Convert::raw2xml($this->Symbol());
        $text = str_replace($xmlSymbol, $this->ImageTag(), $text);
        return $text;
    }
}

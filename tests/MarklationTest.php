<?php
/**
 * Marklation - Markup translator and dictionary standard
 * Copyright (c) 2014 Juho Viitasalo
 */

namespace Tests;

use Marklation\MarkupTranslator;
use Marklation\MarkupDictionary;

class MarkupTranslatorTest extends \PHPUnit_Framework_TestCase {
  public function testTranslate()
  {
    // Arrange
    $dictionary = new MarkupDictionary(__DIR__."/mockups/single-surrounds.json");
    $translator = new MarkupTranslator();
    $translator->setDictionary($dictionary);
    $text = "_Test text_";
    // Act
    $target = $translator->translate($text);

    // Assert
    $this->assertNotEquals($target, $text);
  }
  public function testTranslateSingleSurround()
  {
    // Arrange
    $dictionary = new MarkupDictionary(__DIR__."/mockups/single-surrounds.json");
    $translator = new MarkupTranslator();
    $translator->setDictionary($dictionary);
    $text = "__Test _inner_ text__";
    // Act
    $translation = $translator->translate($text);
    // Assert
    $this->assertEquals("<strong>Test <em>inner</em> text</strong>", $translation);
  }
  public function testTranslateLink()
  {
    // Arrange
    $dictionary = new MarkupDictionary(__DIR__."/mockups/single-surrounds.json");
    $translator = new MarkupTranslator();
    $translator->setDictionary($dictionary);
    $text = "text [Link text](http://url.com \"Link title\") text";
    // Act
    $translation = $translator->translate($text);
    // Assert
    $this->assertEquals("text <a href=\"http://url.com\" title=\"Link title\">Link text</a> text", $translation);
  }

}

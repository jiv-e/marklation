#!/usr/local/bin/php
<?php

require_once __DIR__ . "/vendor/autoload.php";

use MarkupTranslator\MarkupTranslator;
use MarkupTranslator\MarkupDictionary;

$dictionary = new MarkupDictionary(__DIR__."/tests/mockups/single-surrounds.json");
$translator = new MarkupTranslator();
$translator->setDictionary($dictionary);

print $translator->translate($argv[1])."\n";
//print $moro = \MarkupTranslator\MarkupUtilities::encodeEscapes('moo \_ \k \. \^\!\(joo')."\n";
//print \MarkupTranslator\MarkupUtilities::decodeEscapes($moro)."\n";
?>

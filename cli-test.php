#!/usr/local/bin/php
<?php

require_once __DIR__ . "/vendor/autoload.php";

use Marklation\MarkupTranslator;
use Marklation\MarkupDictionary;

$dictionary = new MarkupDictionary(__DIR__."/tests/mockups/single-surrounds.json");
$translator = new MarkupTranslator();
$translator->setDictionary($dictionary);

print $translator->translate($argv[1])."\n";
?>

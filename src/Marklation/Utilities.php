<?php
/**
 * Marklation - Markup translator and dictionary standard
 * Copyright (c) 2014 Juho Viitasalo
 *
 * Some utility functions.
 */

namespace Marklation;


class Utilities {
  public static function sanitize($string) {
    $escapeTable = array(
      '*' => '\*',
      '[' => '\[',
      ']' => '\]',
      '(' => '\(',
      ')' => '\)',
    );

    return strtr($string, $escapeTable);
  }

  public static function encodeEscapes($string) {
    //$encodedChar = Utilities::strToHex($charToEncode);
    $encodedString = preg_replace_callback('/\\\\(.)/', function($matches) {
      return '0x'.Utilities::strToHex($matches[1]);
    }, $string);
    return $encodedString;
  }

  public static function decodeEscapes($string) {
    $decodedString = preg_replace_callback('/0x([0-9A-F]{2})/', function($matches) {
      return Utilities::hexToStr($matches[1]);
    }, $string);
    return $decodedString;
  }

  private static function strToHex($string){
    $hex = '';
    for ($i=0; $i<strlen($string); $i++){
      $ord = ord($string[$i]);
      $hexCode = dechex($ord);
      $hex .= substr('0'.$hexCode, -2);
    }
    return strToUpper($hex);
  }

  private static function hexToStr($hex){
    $string='';
    for ($i=0; $i < strlen($hex)-1; $i+=2){
      $string .= chr(hexdec($hex[$i].$hex[$i+1]));
    }
    return $string;
  }

}

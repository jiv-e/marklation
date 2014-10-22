<?php
/**
 * Created by PhpStorm.
 * User: jiv
 * Date: 22/10/14
 * Time: 10:28
 */

namespace MarkupTranslator;


class MarkupUtilities {
  public static  function sanitize($string) {
    $escapeTable = array(
      '*' => '\*',
      '[' => '\[',
      ']' => '\]',
      '(' => '\(',
      ')' => '\)',
    );

    return strtr($string, $escapeTable);
  }

}

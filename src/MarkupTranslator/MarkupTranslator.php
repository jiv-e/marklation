<?php
/**
 * Created by PhpStorm.
 * User: jiv
 * Date: 18/10/14
 * Time: 22:12
 */

namespace MarkupTranslator;


class MarkupTranslator implements MarkupTranslatorInterface {

  /**
   * @var MarkupDictionary
   */
  private $dictionary;

  /**
   * @param MarkupDictionary $dictionary
   */
  public function setDictionary($dictionary) {
    $this->dictionary = $dictionary;
  }

  /**
   * @return MarkupDictionary
   */
  public function getDictionary() {
    return $this->dictionary;
  }


  public function __construct() {


  }

  public function translate($text) {
    //TODO Implement this: http://php.net/manual/en/function.htmlspecialchars.php
    $translation = $this->replaceSingleSurrounds($text);

    return $translation;
  }

  protected function replaceSingleSurrounds($text) {
    $parsedText = MarkupUtilities::encodeEscapes($text);
    //Element order ensures that extended elements are rendered first
    foreach ($this->getDictionary()->getOrderedElements() as $element) {
      $sourcePattern = '/';
      $targetPattern = '';
      preg_match_all('/\$\d/', $element->getSource(), $sourceArgs);
      preg_match_all('/\$\d/', $element->getTarget(), $targetArgs);
      //TODO Document arg order
      //TODO Raise error if source args are not in numerical order!
      $sourceParts = preg_split('/\$\d/', $element->getSource());
      $startChar = $this->getFirstCharOfPart($sourceParts[0]);
      $targetParts = preg_split('/\$\d/', $element->getTarget());
      if(count($sourceParts) == count($targetParts) > 1) {
        $argNum = count($sourceParts) - 1;
        for ($i = 0; $i < $argNum; $i++) {
          //TODO Document this!
          //Start of the source pattern is not allowed inside the pattern
          $sourcePattern .= $sourceParts[$i].'([^'.$startChar.']*?)';
          $targetPattern .= $targetParts[$i].$targetArgs[0][$i];
        }
        $sourcePattern .= $sourceParts[$i].'/';
        $targetPattern .= $targetParts[$i];
        $parsedText = preg_replace($sourcePattern, $targetPattern, $parsedText);
      }
    }

    $parsedText = MarkupUtilities::decodeEscapes($parsedText);
    return $parsedText;
  }

  protected function countArguments($string) {
    return preg_match_all('/\$\d/', $string);
  }

  /**
   * Gets the first character from regex part that is not an escape '\' or whitespace.
   *
   * @param string $part
   * @return string
   */
  protected function getFirstCharOfPart($part) {
    $part = ltrim($part);
    if(($firstChar = substr($part, 0, 1)) == '\\') {
      return '\\'.$this->getFirstCharOfPart(substr($part, 1));
    }
    return $firstChar;
  }

}

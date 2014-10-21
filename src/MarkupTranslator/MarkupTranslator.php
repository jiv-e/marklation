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
    $translation = $this->replaceSingleSurrounds($text);

    return $translation;
  }

  protected function replaceSingleSurrounds($text) {
    $parsedText = $text;
    //Element order ensures that extended elements are rendered first
    foreach ($this->getDictionary()->getOrderedElements() as $element) {
      $sourcePattern = '/';
      $targetPattern = '';
      $sourceParts = preg_split('/\$\d/', $element->getSource());
      $targetParts = preg_split('/\$\d/', $element->getTarget());
      if(count($sourceParts) == count($targetParts) > 1 && count($sourceParts) % 2 == 0) {
        $argNum = count($sourceParts) - 1;
        for ($i = 0; $i < $argNum; $i++) {
          $sourcePattern .= $sourceParts[$i].'(.*?)';
          $targetPattern .= $targetParts[$i].'$'.($i+1);
        }
        $sourcePattern .= $sourceParts[$i].'/';
        $targetPattern .= $targetParts[$i];
        $parsedText = preg_replace($sourcePattern, $targetPattern, $parsedText);

      }
      /*for ($i = 0; $i < $argNum; ++$i) {
        preg_match('/(^$\d]*?)\$'.$i.'([^$\d]*?)/', $element->getSource(), $source_matches);
        preg_match('/([^$\d]*?)\$'.$i.'([^$\d]*?)/', $element->getTarget(), $target_matches);
        if($source_matches && $target_matches) {
          $parsedText = preg_replace('/'.$source_matches[1].'(.*?)'.$source_matches[2].'/', $target_matches[1].'$1'.$target_matches[2], $parsedText);
        }
      }*/


    }

    return $parsedText;
  }

  protected function countArguments($string) {
    return preg_match_all('/\$\d/', $string);
  }

}
